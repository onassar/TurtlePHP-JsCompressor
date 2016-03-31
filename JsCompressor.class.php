<?php

    // namespace
    namespace Plugin;

    // dependency check
    if (class_exists('\\Plugin\\Config') === false) {
        throw new \Exception(
            '*Config* class required. Please see ' .
            'https://github.com/onassar/TurtlePHP-ConfigPlugin'
        );
    }

    // dependency check
    if (function_exists('jsShrink') === false) {
        throw new \Exception(
            '*jsShrink* function does not exist. Please see ' .
            'https://github.com/vrana/JsShrink/'
        );
    }

    /**
     * JsCompressor
     * 
     * @author   Oliver Nassar <onassar@gmail.com>
     * @abstract
     */
    abstract class JsCompressor
    {
        /**
         * _configPath
         *
         * @var    string
         * @access protected
         * @static
         */
        protected static $_configPath = 'config.default.inc.php';

        /**
         * _config
         *
         * @var    array
         * @access protected
         * @static
         */
        protected static $_config;

        /**
         * _initiated
         *
         * @var    boolean
         * @access protected
         * @static
         */
        protected static $_initiated = false;

        /**
         * init
         * 
         * @access public
         * @static
         * @return void
         */
        public static function init()
        {
            if (is_null(self::$_initiated) === false) {
                self::$_initiated = true;
                require_once self::$_configPath;
                $config = \Plugin\Config::retrieve();
                self::$_config = $config['TurtlePHP-JsCompressorPlugin'];
                \Turtle\Application::addRoutes(self::$_config['routes']);
            }
        }

        /**
         * getBatchPath
         * 
         * @access public
         * @static
         * @param  string $batchName
         * @return string
         */
        public static function getBatchPath($batchName)
        {
            // Config settings
            $files = self::$_config['batches'][$batchName]['files'];
            $storagePath = self::$_config['batches'][$batchName]['storage'];
            $compress = self::$_config['compress'];

            // Check if writable
            if (posix_access($storagePath, POSIX_W_OK) === false) {
                throw new \Exception(
                    '*' . ($storagePath) . '* needs to be writable.'
                );
            }

            // Last modified epoch
            $md5 = '';
            foreach ($files as $file) {
                $md5 .= filemd5($file);
            }
            $md5 = md5($md5);

            // Create paths
            $minifiedPath = ($storagePath) . '/' . ($batchName) . '.' .
                ($md5) . '.min.js';
            $fullPath = ($storagePath) . '/' . ($batchName) . '.' .
                ($md5) . '.js';

            // If this iteration has already been written
            if (is_file($fullPath)) {
                if ($compress) {
                    return str_replace(WEBROOT, '', $minifiedPath);
                }
                return str_replace(WEBROOT, '', $fullPath);
            }

            /**
             * Create and return (recursively)
             * 
             */

            // Generate the contents by looping over each file
            $contents = '';
            foreach ($files as $file) {
                ob_start();
                include ($file);
                $_response = ob_get_contents();
                ob_end_clean();
                $contents .= $_response;
            }

            // Write minified (and copy to clean filename)
            $minifiedFile = fopen($minifiedPath, 'w');
            fwrite($minifiedFile, jsShrink($contents));
            copy(
                $minifiedPath,
                preg_replace('/\.[0-9]+\.min\.js$/', '.min.js', $minifiedPath)
            );

            // Write full (and copy to clean filename)
            $fullFile = fopen($fullPath, 'w');
            fwrite($fullFile, $contents);
            copy(
                $fullPath,
                preg_replace('/\.[0-9]+\.js$/', '.js', $fullPath)
            );

            // Done
            return self::getBatchPath($batchName);
        }

        /**
         * setConfigPath
         * 
         * @access public
         * @param  string $path
         * @return void
         */
        public static function setConfigPath($path)
        {
            self::$_configPath = $path;
        }
    }

    // Config
    $info = pathinfo(__DIR__);
    $parent = ($info['dirname']) . '/' . ($info['basename']);
    $configPath = ($parent) . '/config.inc.php';
    if (is_file($configPath)) {
        JsCompressor::setConfigPath($configPath);
    }

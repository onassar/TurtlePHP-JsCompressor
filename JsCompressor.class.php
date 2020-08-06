<?php

    // namespace
    namespace Plugin;

    /**
     * JsCompressor
     * 
     * JsCompressor plugin for TurtlePHP.
     * 
     * @author  Oliver Nassar <onassar@gmail.com>
     * @abstract
     * @extends Base
     */
    abstract class JsCompressor extends Base
    {
        /**
         * _configPath
         *
         * @access  protected
         * @var     string
         * @static
         */
        protected static $_configPath = 'config.default.inc.php';

        /**
         * _initiated
         *
         * @access  protected
         * @var     bool
         * @static
         */
        protected static $_initiated = false;

        /**
         * _addRoutes
         * 
         * @access  protected
         * @static
         * @return  void
         */
        protected static function _addRoutes(): void
        {
            $configData = static::_getConfigData();
            $routes = $configData['routes'];
            \Turtle\Application::addRoutes($routes);
        }

        /**
         * _batchPathCompiled
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  bool
         */
        protected static function _batchPathCompiled(string $batchKey): bool
        {
            $unminifiedPath = static::_getBatchUnminifiedPath($batchKey);
            if (is_file($unminifiedPath) === true) {
                return true;
            }
            return false;
        }

        /**
         * _checkDependencies
         * 
         * @access  protected
         * @static
         * @return  void
         */
        protected static function _checkDependencies(): void
        {
            static::_checkConfigPluginDependency();
            static::_checkJSShrinkDependency();
        }

        /**
         * _getBatchContent
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  string
         */
        protected static function _getBatchContent(string $batchKey): string
        {
            $configData = static::_getConfigData();
            $files = $configData['batches'][$batchKey]['files'];
            $content = '';
            foreach ($files as $file) {
                ob_start();
                include ($file);
                $_response = ob_get_contents();
                ob_end_clean();
                $content .= $_response;
            }
            return $content;
        }

        /**
         * _getBatchHash
         * 
         * Returns a unique hash for a batch based on the md5 hash of each
         * individual files contents.
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  string
         */
        protected static function _getBatchHash(string $batchKey): string
        {
            $configData = static::_getConfigData();
            $files = $configData['batches'][$batchKey]['files'];
            $hash = '';
            foreach ($files as $file) {
                $hash .= static::_getFileHash($file);
            }
            $hash = md5($hash);
            return $hash;
        }

        /**
         * _getBatchMinifiedPath
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  string
         */
        protected static function _getBatchMinifiedPath(string $batchKey): string
        {
            $storagePath = static::_getBatchStoragePath($batchKey);
            $batchHash = static::_getBatchHash($batchKey);
            $basename = ($batchKey) . '.' . ($batchHash) . '.min.js';
            $minifiedPath = ($storagePath) . '/' . ($basename);
            return $minifiedPath;
        }

        /**
         * _getBatchMinifiedUserAgentPath
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  string
         */
        protected static function _getBatchMinifiedUserAgentPath(string $batchKey): string
        {
            $minifiedPath = static::_getBatchMinifiedPath($batchKey);
            $minifiedUserAgentPath = str_replace(WEBROOT, '', $minifiedPath);
            return $minifiedUserAgentPath;
        }

        /**
         * _getBatchStoragePath
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  string
         */
        protected static function _getBatchStoragePath(string $batchKey): string
        {
            $configData = static::_getConfigData();
            $storagePath = $configData['batches'][$batchKey]['storage'];
            return $storagePath;
        }

        /**
         * _getBatchUnminifiedPath
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  string
         */
        protected static function _getBatchUnminifiedPath(string $batchKey): string
        {
            $storagePath = static::_getBatchStoragePath($batchKey);
            $batchHash = static::_getBatchHash($batchKey);
            $basename = ($batchKey) . '.' . ($batchHash) . '.js';
            $unminifiedPath = ($storagePath) . '/' . ($basename);
            return $unminifiedPath;
        }

        /**
         * _getBatchUnminifiedUserAgentPath
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  string
         */
        protected static function _getBatchUnminifiedUserAgentPath(string $batchKey): string
        {
            $unminifiedPath = static::_getBatchUnminifiedPath($batchKey);
            $unminifiedUserAgentPath = str_replace(WEBROOT, '', $unminifiedPath);
            return $unminifiedUserAgentPath;
        }

        /**
         * _getBatchUserAgentPath
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @param   bool $compressed
         * @return  string
         */
        protected static function _getBatchUserAgentPath(string $batchKey, bool $compressed): string
        {
            $configData = static::_getConfigData();
            $compress = $configData['compress'];
            if ($compress === true) {
                $batchPath = static::_getBatchMinifiedUserAgentPath($batchKey);
                return $batchPath;
            }
            if ($compressed === true) {
                $batchPath = static::_getBatchMinifiedUserAgentPath($batchKey);
                return $batchPath;
            }
            $batchPath = static::_getBatchUnminifiedUserAgentPath($batchKey);
            return $batchPath;
        }

        /**
         * _getFileHash
         * 
         * @access  protected
         * @static
         * @param   string $path
         * @return  string
         */
        protected static function _getFileHash(string $path): string
        {
            $content = file_get_contents($path);
            $hash = md5($content);
            return $hash;
        }

        /**
         * _writeBatchPaths
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  void
         */
        protected static function _writeBatchPaths(string $batchKey): void
        {
            static::_writeMinifiedBatchPath($batchKey);
            static::_writeUnminifiedBatchPath($batchKey);
        }

        /**
         * _writeMinifiedBatchPath
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  void
         */
        protected static function _writeMinifiedBatchPath(string $batchKey): void
        {
            $minifiedPath = static::_getBatchMinifiedPath($batchKey);
            $resource = fopen($minifiedPath, 'w');
            $content = static::_getBatchContent($batchKey);
            $content = jsShrink($content);
            fwrite($resource, $content);
            $pattern = '/\.[a-zA-Z0-9]+\.min\.js$/';
            $replacement = '.min.js';
            $cleanPath = preg_replace($pattern, $replacement, $minifiedPath);
            copy($minifiedPath, $cleanPath);
        }

        /**
         * _writeUnminifiedBatchPath
         * 
         * @access  protected
         * @static
         * @param   string $batchKey
         * @return  void
         */
        protected static function _writeUnminifiedBatchPath(string $batchKey): void
        {
            $unminifiedPath = static::_getBatchUnminifiedPath($batchKey);
            $resource = fopen($unminifiedPath, 'w');
            $content = static::_getBatchContent($batchKey);
            fwrite($resource, $content);
            $pattern = '/\.[a-zA-Z0-9]+\.js$/';
            $replacement = '.js';
            $cleanPath = preg_replace($pattern, $replacement, $unminifiedPath);
            copy($unminifiedPath, $cleanPath);
        }

        /**
         * getBatchPath
         * 
         * @access  public
         * @static
         * @param   string $batchKey
         * @param   bool $compressed (default: false)
         * @return  string
         */
        public static function getBatchPath(string $batchKey, bool $compressed = false)
        {
            $storagePath = static::_getBatchStoragePath($batchKey);
            static::_checkDirectoryWritePermissions($storagePath);
            $batchCompiled = static::_batchPathCompiled($batchKey);
            if ($batchCompiled === true) {
                $args = array($batchKey, $compressed);
                $batchPath = static::_getBatchUserAgentPath(... $args);
                return $batchPath;
            }
            static::_writeBatchPaths($batchKey);
            $batchPath = static::getBatchPath($batchKey);
            return $batchPath;
        }

        /**
         * init
         * 
         * @access  public
         * @static
         * @return  bool
         */
        public static function init(): bool
        {
            if (static::$_initiated === true) {
                return false;
            }
            parent::init();
            static::_addRoutes();
            return true;
        }
    }

    // Config path loading
    $info = pathinfo(__DIR__);
    $parent = ($info['dirname']) . '/' . ($info['basename']);
    $configPath = ($parent) . '/config.inc.php';
    \Plugin\JsCompressor::setConfigPath($configPath);

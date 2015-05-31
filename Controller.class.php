<?php

    /**
     * JsCompressorController
     * 
     * @extends Controller
     * @final
     */
    final class JsCompressorController extends \Turtle\Controller
    {
        /**
         * actionCompress
         * 
         * @access public
         * @param  string $key
         * @return void
         */
        public function actionCompress($key)
        {
            $path = \Plugin\JsCompressor::getBatchPath($key);
            $contents = file_get_contents(WEBROOT . ($path));
            $this->_pass('response', $contents);
            header('Content-type: text/javascript');
        }

        /**
         * actionCompressAll
         * 
         * @access public
         * @return void
         */
        public function actionCompressAll()
        {
            $config = \Plugin\Config::retrieve('TurtlePHP-JsCompressorPlugin');
            $batches = $config['batches'];
            foreach ($batches as $key => $settings) {
                \Plugin\JsCompressor::getBatchPath($key);
            }
        }
    }

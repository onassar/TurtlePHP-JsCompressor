<?php

    /**
     * JsCompressorController
     * 
     * @extends \Turtle\Controller
     * @final
     */
    final class JsCompressorController extends \Turtle\Controller
    {
        /**
         * actionCompress
         * 
         * @access  public
         * @param   string $batchKey
         * @return  void
         */
        public function actionCompress(string $batchKey)
        {
            $path = \Plugin\JsCompressor::getBatchPath($batchKey);
            $path = WEBROOT . ($path);
            $content = file_get_contents($path);
            $this->_pass('response', $content);
            header('Content-type: text/javascript');
        }

        /**
         * actionCompressAll
         * 
         * @access  public
         * @return  void
         */
        public function actionCompressAll()
        {
            $config = \Plugin\Config::retrieve('TurtlePHP-JsCompressorPlugin');
            $batches = $config['batches'];
            $paths = array();
            foreach ($batches as $key => $settings) {
                $paths[$key] = \Plugin\JsCompressor::getBatchPath($key);
            }
            $success = true;
            $data = compact('paths');
            $response = compact('success', 'data');
            $encodedResponse = json_encode($response);
            $this->_pass('response', $encodedResponse);
        }
    }

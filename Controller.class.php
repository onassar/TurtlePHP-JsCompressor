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
         * @access  public
         * @param   string $key
         * @return  void
         */
        public function actionCompress($key)
        {
            $path = \Plugin\JsCompressor::getBatchPath($key);
            $content = file_get_contents(WEBROOT . ($path));
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
            $response = array(
                'success' => true,
                'data' => array(
                    'paths' => $paths
                )
            );
            $this->_pass('response', json_encode($response));
        }
    }

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
         * @return void
         */
        public function actionCompress()
        {
            $config = \Plugin\Config::retrieve('TurtlePHP-JsCompressorPlugin');
            $batches = $config['batches'];
            foreach ($batches as $key => $settings) {
                \Plugin\JsCompressor::getBatchPath($key);
            }
            exit(0);
        }
    }

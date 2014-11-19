<?php

    /**
     * Namespace
     * 
     */
    namespace Plugin\JsCompressor;

    /**
     * Compression
     * 
     */
    $compress = true;

    /**
     * Batches
     * 
     */

    // Batches of files to compressor and write
    $batches = array(
        'app' => array(
            'storage' => WEBROOT . '/static/app/js/compiled',
            'files' => array(

                // Dependencies
                WEBROOT . '/static/app/vendors/extend.js',
                WEBROOT . '/static/app/vendors/jquery-1.11.1.min.js'
            )
        )
    );

    /**
     * Config storage
     * 
     */

    // Store
    \Plugin\Config::add(
        'TurtlePHP-JsCompressorPlugin',
        array(
            'compress' => $compress,
            'batches' => $batches
        )
    );

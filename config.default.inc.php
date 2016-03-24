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
     * Routes
     * 
     */
    $routes =  array(
        '^/compress/all$' => array(// G
            'controller' => 'JsCompressor',
            'action' => 'actionCompressAll',
            'view' => dirname(__FILE__) . '/raw.inc.php'
        ),
        '^/compress/([a-zA-Z0-9\-_]+)$' => array(// G
            'controller' => 'JsCompressor',
            'action' => 'actionCompress',
            'view' => dirname(__FILE__) . '/raw.inc.php'
        )
    );

    /**
     * Batches
     * 
     */

    // Batches of files to compressor and write
    $batches = array(
        'app' => array(
            'storage' => WEBROOT . '/app/static/js/compiled',
            'files' => array(

                // Dependencies
                WEBROOT . '/app/static/vendors/extend.js',
                WEBROOT . '/app/static/vendors/jquery-1.11.1.min.js'
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
            'routes' => $routes,
            'batches' => $batches
        )
    );

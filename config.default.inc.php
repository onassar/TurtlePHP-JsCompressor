<?php

    /**
     * Namespace
     * 
     */
    namespace Plugin\JsCompressor;

    /**
     * Plugin Config Data
     * 
     */

    /**
     * $compress
     * 
     * @var     bool (default: true)
     * @access  private
     */
    $compress = true;

    /**
     * $routes
     * 
     * @var     array
     * @access  private
     */
    $routes = array(
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
     * $batches
     * 
     * @var     array
     * @access  private
     */
    $batches = array(
        'app' => array(
            'storage' => WEBROOT . '/app/static/js/compiled',
            'files' => array(

                // Dependencies
                WEBROOT . '/app/static/vendors/source/external/extend/v0.0.0/extend.js',
                WEBROOT . '/app/static/vendors/source/external/jquery/v1.11.3/jQuery.min.js'
            )
        )
    );

    /**
     * $pluginConfigData
     * 
     * @var     array
     * @access  private
     */
    $pluginConfigData = compact('compress', 'routes', 'batches');

    /**
     * Storage
     * 
     */
    $key = 'TurtlePHP-JsCompressorPlugin';
    \Plugin\Config::add($key, $pluginConfigData);

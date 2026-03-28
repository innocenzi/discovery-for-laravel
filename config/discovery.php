<?php

return [
    /**
     * Defines classes that Discovery should ignore. You can ignore Discovery
     * classes, but also classes that other Discovery classes should ignore.
     */
    'skip_classes' => [
        // \Innocenzi\Discovery\Config\ConfigDiscovery::class,
        // \Innocenzi\Discovery\Routing\RouteDiscovery::class,
        // \Innocenzi\Discovery\Container\InitializerDiscovery::class,
        // \Innocenzi\Discovery\Console\CommandDiscovery::class,
        // \Innocenzi\Discovery\Routing\MiddlewareDiscovery::class,
        \Innocenzi\Discovery\Hybridly\HybridViewsDiscovery::class,
    ],

    /**
     * Defines patterns that Discovery will ignore. Discovery uses Reflection, which loads code
     * and may cause issues in some scenarios, such as scanning Pest test files. For this,
     * reason you can define patterns that will be ignored during the discovery process.
     */
    'skip_matches' => [
        '*Test.php',
        '*Pest.php',
    ],

    /**
     * Sets the cache directory in which Tempest will store Discovery metadata.
     * This is particularly important in production to avoid any overhead.
     */
    'cache_path' => base_path('bootstrap/cache/discovery'),

    /**
     * Defines the caching strategy that Discovery should use. By default, Discovery will use caching in production
     * and no caching in local and testing environments. You can change this behavior by setting a specific strategy here.
     * - `full`: Discovery will cache all discovered resources, and will use the cache on every request.
     * - `partial`: Discovery will only cache vendor resources. This can be helpful during development.
     * - `none`: Discovery will not use caching at all, and will perform discovery on every request.
     */
    'cache_strategy' => null,

    /**
     * Sets the directory in which Discovery should look for a `composer.json` file and a `vendor` directory.
     * Discovery will determine the application namespaces thanks to `composer.json`, and scan dependencies
     * to find other discovery classes and other discoverable resources.
     */
    'autoload_path' => base_path(),
];

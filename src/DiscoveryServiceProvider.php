<?php

declare(strict_types=1);

namespace Discovery;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Tempest\Discovery\DiscoveryCache;
use Tempest\Discovery\DiscoveryCacheStrategy;
use Tempest\Discovery\DiscoveryConfig;

final class DiscoveryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/discovery.php' => config_path('discovery.php'),
        ]);
    }

    public function register(): void
    {
        $this->mergeConfigFrom(
            path: __DIR__ . '/../config/discovery.php',
            key: 'discovery',
        );

        $this->registerDiscovery();
    }

    private function registerDiscovery(): void
    {
        $discovery = $this->initializeDiscovery();
        $discovery->discover();

        $this->app->singleton(Discovery::class, static fn () => $discovery);
    }

    private function initializeDiscovery(): Discovery
    {
        return new Discovery(
            container: $this->app,
            config: DiscoveryConfig::autoload(config('discovery.autoload_path'))
                ->skipUsing(static fn (string $input) => Str::is(config('discovery.skip_matches', default: []), value: $input))
                ->skipClasses(...config('discovery.skip_classes', default: []))
                ->skipPaths(...config('discovery.skip_paths', default: [])),
            cache: new DiscoveryCache(
                strategy: match (config('discovery.cache_strategy')) {
                    'full' => DiscoveryCacheStrategy::FULL,
                    'partial' => DiscoveryCacheStrategy::PARTIAL,
                    'none' => DiscoveryCacheStrategy::NONE,
                    default => $this->app->environment('production')
                        ? DiscoveryCacheStrategy::FULL
                        : DiscoveryCacheStrategy::NONE,
                },
                pool: new PhpFilesAdapter(
                    directory: config('discovery.cache_path'),
                ),
            ),
        );
    }
}

<?php

declare(strict_types=1);

namespace Discovery;

use Discovery\Hybridly\DefaultHybridComponentNameResolver;
use Discovery\Hybridly\HybridComponentNameResolver;
use Discovery\Hybridly\StaticComponentsResolver;
use Hybridly\Architecture\ComponentsResolver;
use Hybridly\Hybridly;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Tempest\Discovery\Composer;
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

        $this->registerHybridly();
        $this->registerDiscovery();
    }

    private function registerHybridly(): void
    {
        if (! class_exists(Hybridly::class)) {
            return;
        }

        $this->app->singleton(ComponentsResolver::class, static fn () => new StaticComponentsResolver());

        $this->app->singleton(HybridComponentNameResolver::class, static function () {
            $root = config('discovery.autoload_path');

            return new DefaultHybridComponentNameResolver(
                composer: new Composer($root)->load(),
                root_path: $root,
            );
        });
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

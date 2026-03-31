<?php

declare(strict_types=1);

namespace Tests\Config;

use Discovery\ConfigDiscovery;
use Illuminate\Config\Repository;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Discovery\DiscoveryItems;
use Tempest\Discovery\DiscoveryLocation;

/** @internal */
final class ConfigDiscoveryTest extends TestCase
{
    #[Test]
    public function finds_configs(): void
    {
        $discovery = new ConfigDiscovery(
            application: $this->app,
            config: $config = $this->app->make(Repository::class),
        );

        $discovery->setItems(new DiscoveryItems([]));

        $discovery->discoverPath(
            location: new DiscoveryLocation('App', path: 'config/'),
            path: __DIR__ . '/config/ignored.php',
        );

        $discovery->discoverPath(
            location: new DiscoveryLocation('App', path: 'src/'),
            path: __DIR__ . '/fixture.config.php',
        );

        $discovery->apply();

        $this->assertNull($config->get('ignored.found'));
        $this->assertTrue($config->get('fixture.found'));
    }
}

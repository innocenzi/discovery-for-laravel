<?php

declare(strict_types=1);

namespace Tests\Console;

use Illuminate\Console\Application as Artisan;
use Innocenzi\Discovery\Console\CommandDiscovery;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Discovery\DiscoveryItems;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Reflection\ClassReflector;

/** @internal */
final class CommandDiscoveryTest extends TestCase
{
    #[Test]
    public function register_commands(): void
    {
        Artisan::forgetBootstrappers();

        $discovery = new CommandDiscovery();
        $discovery->setItems(new DiscoveryItems([]));

        $discovery->discover(
            location: new DiscoveryLocation('App', path: 'src/'),
            class: new ClassReflector(FixtureCommand::class),
        );

        $discovery->apply();

        $artisan = new Artisan($this->app, $this->app['events'], $this->app->version());

        $this->assertTrue($artisan->has('fixture:command'));

        Artisan::forgetBootstrappers();
    }
}

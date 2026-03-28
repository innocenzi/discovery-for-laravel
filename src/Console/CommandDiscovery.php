<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Console;

use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Tempest\Discovery\Discovery;
use Tempest\Discovery\DiscoveryLocation;
use Tempest\Discovery\IsDiscovery;
use Tempest\Reflection\ClassReflector;

final class CommandDiscovery implements Discovery
{
    use IsDiscovery;

    public function discover(DiscoveryLocation $location, ClassReflector $class): void
    {
        if ($class->is(Command::class)) {
            $this->discoveryItems->add($location, $class->getName());
        }
    }

    public function apply(): void
    {
        $commands = [];

        foreach ($this->discoveryItems as $class) {
            $commands[] = $class;
        }

        Artisan::starting(static function (Artisan $artisan) use ($commands) {
            $artisan->resolveCommands($commands);
        });
    }
}

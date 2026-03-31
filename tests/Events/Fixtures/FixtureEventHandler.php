<?php

declare(strict_types=1);

namespace Tests\Events\Fixtures;

use Discovery\Events\EventHandler;

final class FixtureEventHandler
{
    public static int $discovered_calls = 0;

    public static int $undiscovered_calls = 0;

    #[EventHandler]
    public function discovered(FixtureEvent $event): void
    {
        self::$discovered_calls++;
    }

    public function undiscovered(FixtureEvent $event): void
    {
        self::$undiscovered_calls++;
    }

    public static function reset(): void
    {
        self::$discovered_calls = 0;
        self::$undiscovered_calls = 0;
    }
}

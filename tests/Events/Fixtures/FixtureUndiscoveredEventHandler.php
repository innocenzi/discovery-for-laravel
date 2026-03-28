<?php

declare(strict_types=1);

namespace Tests\Events\Fixtures;

final class FixtureUndiscoveredEventHandler
{
    public static int $calls = 0;

    public function handle(FixtureEvent $event): void
    {
        self::$calls++;
    }

    public static function reset(): void
    {
        self::$calls = 0;
    }
}

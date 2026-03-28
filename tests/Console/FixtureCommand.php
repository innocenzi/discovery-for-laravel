<?php

declare(strict_types=1);

namespace Tests\Console;

use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('fixture:command')]
final class FixtureCommand extends Command
{
    public function handle(): int
    {
        return self::SUCCESS;
    }
}

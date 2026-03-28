<?php

declare(strict_types=1);

namespace Innocenzi\Discovery;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('discovery:clear')]
#[Description('Clears the discovery cache')]
final class ClearDiscoveryCommand extends Command
{
    public function __invoke(Discovery $discovery): void
    {
        $discovery->clearDiscoveryCache();

        $this->components->info('Discovery cache cleared successfully.');
    }
}

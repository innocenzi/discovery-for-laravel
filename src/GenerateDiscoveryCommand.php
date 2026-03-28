<?php

declare(strict_types=1);

namespace Innocenzi\Discovery;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('discovery:generate')]
#[Description('Generates the discovery cache')]
final class GenerateDiscoveryCommand extends Command
{
    public function __invoke(Discovery $discovery): void
    {
        $discovery->clearDiscoveryCache();
        $discovery->generateDiscoveryCache();

        $this->components->info('Discovery cache generated successfully.');
    }
}

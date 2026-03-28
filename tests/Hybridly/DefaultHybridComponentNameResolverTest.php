<?php

namespace Tests\Hybridly;

use Innocenzi\Discovery\Hybridly\DefaultHybridComponentNameResolver;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tempest\Discovery\Composer;
use Tempest\Support\Namespace\Psr4Namespace;

/** @internal */
final class DefaultHybridComponentNameResolverTest extends TestCase
{
    #[Test]
    public function resolves_namespaced_identifier_from_composer_namespace_path(): void
    {
        $composer = new Composer(base_path())->setNamespaces([
            new Psr4Namespace('Module\\', 'src/Modules'),
        ]);

        $resolver = new DefaultHybridComponentNameResolver(
            composer: $composer,
            root_path: base_path(),
        );

        $name = $resolver->resolve(base_path('src/Modules/Billing/Pages/Checkout.view.vue'));

        $this->assertSame('src/Modules/Billing/Pages/Checkout.view.vue', $name->path);
        $this->assertSame('billing', $name->namespace);
        $this->assertSame('billing::pages.checkout', $name->identifier);
    }

    #[Test]
    public function falls_back_to_default_namespace_for_files_discovered_at_location_root(): void
    {
        $composer = new Composer(base_path())->setNamespaces([
            new Psr4Namespace('Module\\', 'src/Modules'),
        ]);

        $resolver = new DefaultHybridComponentNameResolver(
            composer: $composer,
            root_path: base_path(),
        );

        $name = $resolver->resolve(base_path('resources/js/Home.view.vue'));

        $this->assertSame('resources/js/Home.view.vue', $name->path);
        $this->assertSame('default', $name->namespace);
        $this->assertSame('home', $name->identifier);
    }
}

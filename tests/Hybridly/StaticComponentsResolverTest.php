<?php

declare(strict_types=1);

namespace Tests\Hybridly;

use Discovery\Hybridly\StaticComponentsResolver;
use Hybridly\Architecture\ComponentsResolver;
use Hybridly\Architecture\IdentifierGenerator;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * @internal
 * @mago-expect lint:variable-name
 */
final class StaticComponentsResolverTest extends TestCase
{
    #[Test]
    public function stores_views_and_layouts_with_last_registered_priority(): void
    {
        $resolver = new StaticComponentsResolver();

        $resolver
            ->addView('resources/js/Pages/Dashboard.view.vue', 'default', 'dashboard')
            ->addView('resources/js/Pages/Users.view.vue', 'default', 'users')
            ->addView('resources/js/Pages/Override.view.vue', 'default', 'users')
            ->addLayout('resources/js/Layouts/App.layout.vue', 'default', 'app')
            ->addLayout('resources/js/Layouts/Override.layout.vue', 'default', 'app');

        $this->assertSame(
            [
                [
                    'path' => 'resources/js/Pages/Override.view.vue',
                    'namespace' => 'default',
                    'identifier' => 'users',
                ],
                [
                    'path' => 'resources/js/Pages/Dashboard.view.vue',
                    'namespace' => 'default',
                    'identifier' => 'dashboard',
                ],
            ],
            $resolver->getViews(),
        );

        $this->assertSame(
            [
                [
                    'path' => 'resources/js/Layouts/Override.layout.vue',
                    'namespace' => 'default',
                    'identifier' => 'app',
                ],
            ],
            $resolver->getLayouts(),
        );
    }

    #[Test]
    public function supports_has_view_and_selective_unload(): void
    {
        $resolver = new StaticComponentsResolver();

        $resolver
            ->addView('resources/js/Pages/Checkout.view.vue', 'billing', 'billing::checkout')
            ->addLayout('resources/js/Layouts/Billing.layout.vue', 'billing', 'billing::billing');

        $this->assertTrue($resolver->hasView('billing::checkout'));
        $this->assertFalse($resolver->hasView('billing::missing'));

        $resolver->unload(views: false, layouts: true);

        $this->assertCount(1, $resolver->getViews());
        $this->assertCount(0, $resolver->getLayouts());
    }

    #[Test]
    public function ignores_filesystem_load_methods_and_identifier_generator_override(): void
    {
        $resolver = new StaticComponentsResolver();
        $resolver->addView('resources/js/Pages/Home.view.vue', 'default', 'home');

        $this->assertSame($resolver, $resolver->loadViewsFrom(directory: base_path('resources/js')));
        $this->assertSame($resolver, $resolver->loadLayoutsFrom(directory: base_path('resources/js')));
        $this->assertSame($resolver, $resolver->loadModuleFrom(directory: base_path('resources/js'), namespace: 'default'));
        $this->assertSame($resolver, $resolver->setIdentifierGenerator(new class implements IdentifierGenerator {
            public function generate(ComponentsResolver $components, string $path, string $baseDirectory, string $namespace): string
            {
                return 'generated';
            }
        }));

        $this->assertSame(
            [
                [
                    'path' => 'resources/js/Pages/Home.view.vue',
                    'namespace' => 'default',
                    'identifier' => 'home',
                ],
            ],
            $resolver->getViews(),
        );
    }
}

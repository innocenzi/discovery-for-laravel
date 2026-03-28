<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Hybridly;

use Tempest\Discovery\Composer;
use Tempest\Support\Namespace\Psr4Namespace;

use function Tempest\Support\Path\normalize;

final class DefaultHybridComponentNameResolver implements HybridComponentNameResolver
{
    public function __construct(
        private readonly Composer $composer,
        private readonly string $root_path,
    ) {}

    /**
     * @mago-expect lint:no-else-clause
     */
    public function resolve(string $path): HybridComponentName
    {
        $path = str($path)
            ->replace('\\\\', '/')
            ->toString();

        $path_after_namespace = $this->pathAfterNamespace($path);

        if ($path_after_namespace !== null) {
            $segments = array_values(array_filter(explode('/', $path_after_namespace)));

            if (count($segments) > 1) {
                $namespace = str(array_shift($segments))->kebab()->toString();
                $identifier_source = implode('/', $segments);
            } else {
                $namespace = 'default';
                $identifier_source = $segments[0] ?? str($path)->basename()->toString();
            }
        } else {
            $namespace = 'default';
            $identifier_source = str($path)->basename()->toString();
        }

        $path_for_hybridly = str($path)
            ->after(base_path('/'))
            ->toString();

        if ($path_for_hybridly === $path) {
            $path_for_hybridly = str($path)
                ->after("{$this->root_path}/")
                ->toString();
        }

        $identifier = str($identifier_source)
            ->replace(['/', '\\\\'], '.')
            ->chopEnd(['.view.vue', '.layout.vue'])
            ->explode('.')
            ->filter(static fn (string $segment) => $segment !== '')
            ->map(static fn (string $segment) => str($segment)->kebab()->toString())
            ->join('.');

        return new HybridComponentName(
            path: $path_for_hybridly,
            namespace: $namespace,
            identifier: str($identifier)
                ->when($namespace !== 'default')
                ->prepend("{$namespace}::")
                ->toString(),
        );
    }

    private function pathAfterNamespace(string $path): ?string
    {
        $paths = [];

        /** @var Psr4Namespace $namespace */
        foreach ($this->composer->namespaces as $namespace) {
            $namespace_path = str(normalize($this->root_path, $namespace->path))
                ->replace('\\\\', '/')
                ->rtrim('/')
                ->toString();

            if (! str_starts_with($path, "{$namespace_path}/")) {
                continue;
            }

            $paths[] = $namespace_path;
        }

        if ($paths === []) {
            return null;
        }

        usort($paths, static fn (string $a, string $b) => strlen($b) <=> strlen($a));

        return str($path)
            ->after($paths[0])
            ->ltrim('/\\')
            ->toString();
    }
}

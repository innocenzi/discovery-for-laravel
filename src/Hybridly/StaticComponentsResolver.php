<?php

declare(strict_types=1);

namespace Discovery\Hybridly;

use Closure;
use Hybridly\Architecture\ComponentsResolver;
use Hybridly\Architecture\IdentifierGenerator;

final class StaticComponentsResolver implements ComponentsResolver
{
    /** @var array<array{path: string, namespace: string, identifier: string}> */
    private array $views = [];

    /** @var array<array{path: string, namespace: string, identifier: string}> */
    private array $layouts = [];

    public function addView(string $path, string $namespace, string $identifier): static
    {
        $this->views[] = [
            'path' => $path,
            'namespace' => $namespace,
            'identifier' => $identifier,
        ];

        return $this;
    }

    public function addLayout(string $path, string $namespace, string $identifier): static
    {
        $this->layouts[] = [
            'path' => $path,
            'namespace' => $namespace,
            'identifier' => $identifier,
        ];

        return $this;
    }

    public function loadViewsFrom(string $directory, null|string|array $namespace = null, ?int $depth = null, ?Closure $filter = null): static
    {
        return $this;
    }

    public function loadLayoutsFrom(string $directory, null|string|array $namespace = null, ?Closure $filter = null): static
    {
        return $this;
    }

    public function loadModuleFrom(string $directory, null|string|array $namespace): static
    {
        return $this;
    }

    public function getViews(): array
    {
        return $this->evaluate($this->views);
    }

    public function hasView(string $identifier): bool
    {
        return collect($this->getViews())->contains(static fn (array $view): bool => $view['identifier'] === $identifier);
    }

    public function getLayouts(): array
    {
        return $this->evaluate($this->layouts);
    }

    public function getExtensions(): array
    {
        return ['.vue'];
    }

    public function unload(bool $views = true, bool $layouts = true): static
    {
        if ($views) {
            $this->views = [];
        }

        if ($layouts) {
            $this->layouts = [];
        }

        return $this;
    }

    public function setIdentifierGenerator(IdentifierGenerator $identifierGenerator): static
    {
        return $this;
    }

    /**
     * @param array<array{path: string, namespace: string, identifier: string}> $collection
     * @return array<array{path: string, namespace: string, identifier: string}>
     */
    private function evaluate(array $collection): array
    {
        return collect($collection)
            ->reverse()
            ->unique('path')
            ->unique('identifier')
            ->values()
            ->all();
    }
}

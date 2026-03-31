<?php

declare(strict_types=1);

namespace Tests\Routing\Fixtures;

use Discovery\Routing\Get;
use Discovery\Routing\Post;
use Discovery\Routing\Prefix;

#[Prefix(name: 'fixture', uri: 'fixture')]
final class FixtureRoutesController
{
    #[Get(uri: 'list', name: 'list')]
    public function index(): void
    {
    }

    #[Post(uri: 'submit', name: 'submit')]
    public function store(): void
    {
    }
}

<?php

namespace Tests\Routing\Fixtures;

use Innocenzi\Discovery\Routing\Get;
use Innocenzi\Discovery\Routing\Post;
use Innocenzi\Discovery\Routing\Prefix;

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

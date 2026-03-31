<?php

declare(strict_types=1);

namespace Tests\Routing\Fixtures;

use Discovery\Routing\Middleware;

#[Middleware('api')]
final class FixtureMiddleware {}

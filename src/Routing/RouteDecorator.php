<?php

declare(strict_types=1);

namespace Discovery\Routing;

interface RouteDecorator
{
    public function decorate(Route $route): Route;
}

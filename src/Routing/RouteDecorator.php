<?php

namespace Innocenzi\Discovery\Routing;

interface RouteDecorator
{
    public function decorate(Route $route): Route;
}

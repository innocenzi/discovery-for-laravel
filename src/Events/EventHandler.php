<?php

declare(strict_types=1);

namespace Innocenzi\Discovery\Events;

use Attribute;

/**
 * Registers the method as an event handler for the given event. The method must have a single parameter, which is the event it handles.
 */
#[Attribute(Attribute::TARGET_METHOD)]
final class EventHandler {}

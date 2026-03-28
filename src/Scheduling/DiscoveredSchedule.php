<?php

namespace Innocenzi\Discovery\Scheduling;

use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tempest\Reflection\MethodReflector;

final class DiscoveredSchedule
{
    public function __construct(
        public readonly string $class,
        public readonly string $method,
        public readonly string|Every $schedule,
        public readonly Type $type,
        public readonly ?string $name = null,
        public readonly ?string $time = null,
        public readonly ?bool $withoutOverlapping = null,
        public readonly ?bool $onOneServer = null,
        public readonly ?bool $runInBackground = null,
    ) {}

    public static function fromMethod(MethodReflector $method, Schedule $attribute): self
    {
        $type = Type::CALL;

        if ($method->getDeclaringClass()->is(Command::class)) {
            $type = Type::COMMAND;
        }

        if ($method->getDeclaringClass()->is(ShouldQueue::class)) {
            $type = Type::JOB;
        }

        return new self(
            class: $method->getDeclaringClass()->getName(),
            method: $method->getName(),
            schedule: $attribute->schedule,
            type: $type,
            name: $attribute->name,
            time: $attribute->time,
            withoutOverlapping: $attribute->withoutOverlapping,
            onOneServer: $attribute->onOneServer,
            runInBackground: $attribute->runInBackground,
        );
    }
}

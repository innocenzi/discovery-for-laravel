<?php

declare(strict_types=1);

namespace Discovery\Scheduling;

use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldQueue;
use Tempest\Reflection\ClassReflector;
use Tempest\Reflection\MethodReflector;

/**
 * @mago-expect lint:property-name
 */
final class DiscoveredSchedule
{
    public function __construct(
        public readonly string $class,
        public readonly ?string $method = null,
        public readonly string|Every $schedule,
        public readonly Type $type,
        public readonly ?string $name = null,
        public readonly ?string $time = null,
        public readonly ?bool $withoutOverlapping = null,
        public readonly ?bool $onOneServer = null,
        public readonly ?bool $runInBackground = null,
        public readonly array $when = [],
    ) {}

    public static function fromClass(ClassReflector $class, Schedule $attribute): self
    {
        $type = Type::CALL;

        if ($class->is(Command::class)) {
            $type = Type::COMMAND;

            if ($class->getMethod('handle')) {
                $method = 'handle';
            }
        }

        if ($class->is(ShouldQueue::class)) {
            $type = Type::JOB;

            if ($class->getMethod('handle')) {
                $method = 'handle';
            }
        }

        return new self(
            class: $class->getName(),
            method: $method,
            schedule: $attribute->schedule,
            type: $type,
            name: $attribute->name,
            time: $attribute->time,
            withoutOverlapping: $attribute->withoutOverlapping,
            onOneServer: $attribute->onOneServer,
            runInBackground: $attribute->runInBackground,
            when: $attribute->when,
        );
    }

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
            method: $method->getName() === '__invoke' ? null : $method->getName(),
            schedule: $attribute->schedule,
            type: $type,
            name: $attribute->name,
            time: $attribute->time,
            withoutOverlapping: $attribute->withoutOverlapping,
            onOneServer: $attribute->onOneServer,
            runInBackground: $attribute->runInBackground,
            when: $attribute->when,
        );
    }
}

<?php

declare(strict_types=1);

namespace Buses\Domain;

use Psr\EventDispatcher\EventDispatcherInterface;

interface EventBus extends EventDispatcherInterface
{
    public function addSubscriber(string $eventFqcn, callable $callable): void;
}

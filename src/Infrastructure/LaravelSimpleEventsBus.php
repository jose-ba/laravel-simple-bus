<?php

declare(strict_types=1);

namespace Buses\Infrastructure;

use Buses\Domain\EventBus;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Name\NamedMessageNameResolver;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;

/**
 * Adaptador de Simple Bus para Laravel
 */
class LaravelSimpleEventsBus implements EventBus
{
    protected array $subscribers = [];

    protected MessageBusSupportingMiddleware $eventBus;

    public function __construct()
    {
    }


    public function addSubscriber(string $eventFqcn, callable $callable): void
    {
        $this->subscribers[$eventFqcn][] = $callable;
    }

    /**
     * @inheritDoc
     */
    public function dispatch(object $event): object
    {
        $this->configureBus();
        $this->eventBus->handle($event);
        return $event;
    }

    protected function configureBus(): void
    {
        $this->eventBus = new MessageBusSupportingMiddleware();
        $this->eventBus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());

        $serviceLocator = function ($handlerClassName) {
            return app()->make($handlerClassName);
        };

        $eventSubscribersResolver = new NameBasedMessageSubscriberResolver(
            new ClassBasedNameResolver(),
            new CallableCollection(
                $this->subscribers,
                new ServiceLocatorAwareCallableResolver($serviceLocator)
            )
        );

        $this->eventBus->appendMiddleware(new NotifiesMessageSubscribersMiddleware($eventSubscribersResolver));
    }
}

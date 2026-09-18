<?php

namespace App\EventSubscriber;

use App\Service\ApplicationInitializer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class AppInitilizeSubscriber implements EventSubscriberInterface
{

    function __construct(
        private ApplicationInitializer $applicationInitializer
    )
    {}

    public function onRequestEvent(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $this->applicationInitializer->initialize();
        
    }

    public static function getSubscribedEvents(): array
    {
        return [
            RequestEvent::class => 'onRequestEvent',
        ];
    }
}

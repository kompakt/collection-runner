<?php

namespace Kompakt\CollectionRunner\Subscriber;

use Kompakt\CollectionRunner\EventNamesInterface;
use Kompakt\CollectionRunner\Event\EndErrorEvent;
use Kompakt\CollectionRunner\Event\ItemErrorEvent;
use Kompakt\CollectionRunner\Event\PageBeginErrorEvent;
use Kompakt\CollectionRunner\Event\PageDoneErrorEvent;
use Kompakt\CollectionRunner\Event\StartErrorEvent;
use Kompakt\CollectionRunner\Event\StartEvent;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ErrorLogger
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $logger = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames,
        Logger $logger
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->logger = $logger;
    }

    public function activate()
    {
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    public function onStartError(StartErrorEvent $event)
    {
        $this->logger->error($event->getException()->getMessage());
    }

    public function onPageBeginError(PageBeginErrorEvent $event)
    {
        $this->logger->error($event->getException()->getMessage());
    }

    public function onItemError(ItemErrorEvent $event)
    {
        $this->logger->error(
            sprintf(
                '%s',
                $event->getException()->getMessage()
            )
        );
    }

    public function onPageDoneError(PageDoneErrorEvent $event)
    {
        $this->logger->error($event->getException()->getMessage());
    }

    public function onEndError(EndErrorEvent $event)
    {
        $this->logger->error($event->getException()->getMessage());
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->startError(),
            [$this, 'onStartError']
        );

        $this->dispatcher->$method(
            $this->eventNames->pageBeginError(),
            [$this, 'onPageBeginError']
        );

        $this->dispatcher->$method(
            $this->eventNames->itemError(),
            [$this, 'onItemError']
        );

        $this->dispatcher->$method(
            $this->eventNames->pageDoneError(),
            [$this, 'onPageDoneError']
        );

        $this->dispatcher->$method(
            $this->eventNames->endError(),
            [$this, 'onEndError']
        );
    }
}
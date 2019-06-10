<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Subscriber;

use Kompakt\CollectionRunner\EventNamesInterface;
use Kompakt\CollectionRunner\Event\EndEvent;
use Kompakt\CollectionRunner\Event\ItemEvent;
use Kompakt\CollectionRunner\Event\PageBeginEvent;
use Kompakt\CollectionRunner\Event\PageDoneEvent;
use Kompakt\CollectionRunner\Event\StartEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ErrorMaker
{
    protected $dispatcher = null;
    protected $eventNames = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
    }

    public function activate()
    {
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    public function onStart(StartEvent $event)
    {
        #throw new \Exception('Start');
    }

    public function onPageBegin(PageBeginEvent $event)
    {
        #throw new \Exception('PageBegin');
    }

    public function onItem(ItemEvent $event)
    {
        throw new \Exception('Item');
    }

    public function onPageDone(PageDoneEvent $event)
    {
        throw new \Exception('PageDone');
    }

    public function onEnd(EndEvent $event)
    {
        throw new \Exception('End');
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->start(),
            [$this, 'onStart']
        );

        $this->dispatcher->$method(
            $this->eventNames->pageBegin(),
            [$this, 'onPageBegin']
        );

        $this->dispatcher->$method(
            $this->eventNames->item(),
            [$this, 'onItem']
        );

        $this->dispatcher->$method(
            $this->eventNames->pageDone(),
            [$this, 'onPageDone']
        );

        $this->dispatcher->$method(
            $this->eventNames->end(),
            [$this, 'onEnd']
        );
    }
}
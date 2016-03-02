<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Subscriber;

use Doctrine\ORM\EntityManager;
use Kompakt\CollectionRunner\EventNamesInterface;
use Kompakt\CollectionRunner\Event\PageDoneEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineClearer
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $em = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames,
        EntityManager $em
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->em = $em;
    }

    public function activate()
    {
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    public function onPageDone(PageDoneEvent $event)
    {
        $this->em->clear();
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->pageDone(),
            [$this, 'onPageDone']
        );
    }
}
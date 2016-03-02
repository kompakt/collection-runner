<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Console\Subscriber;

use Kompakt\CollectionRunner\EventNamesInterface;
use Kompakt\CollectionRunner\Event\ItemEvent;
use Symfony\Component\Console\Helper\ProgressBar as SymfonyProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProgressAdvancer
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $progressBar = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
    }

    public function activate(SymfonyProgressBar $progressBar)
    {
        $this->progressBar = $progressBar;
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    public function onItem(ItemEvent $event)
    {
        $this->progressBar->advance();
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->item(),
            [$this, 'onItem']
        );
    }
}
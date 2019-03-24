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
use Kompakt\CollectionRunner\Event\EndEvent;
use Symfony\Component\Console\Helper\ProgressBar as SymfonyProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ProgressBar
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

    public function activate(OutputInterface $output, $numItems)
    {
        $this->progressBar = new SymfonyProgressBar($output, $numItems);
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    public function onItem(ItemEvent $event)
    {
        if (!$this->progressBar)
        {
            return;
        }

        $this->progressBar->advance();
    }

    public function onEnd(EndEvent $event)
    {
        if (!$this->progressBar)
        {
            return;
        }

        $this->progressBar->finish();
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->item(),
            [$this, 'onItem']
        );

        $this->dispatcher->$method(
            $this->eventNames->end(),
            [$this, 'onEnd']
        );
    }
}
<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Subscriber\Console;

use Kompakt\CollectionRunner\EventNamesInterface;
use Kompakt\CollectionRunner\Event\EndEvent;
use Kompakt\CollectionRunner\Event\StartEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Stopwatch\Stopwatch as SymfonyStopwatch;

class Stopwatch
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $stopwatch = null;
    protected $output = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
        $this->stopwatch = new SymfonyStopwatch();
    }

    public function activate(OutputInterface $output)
    {
        $this->handleListeners(true);
        $this->output = $output;
    }

    public function deactivate()
    {
        $this->handleListeners(false);
        $this->output = null;
    }

    public function onStart(StartEvent $event)
    {
        $this->stopwatch->start('CollectionRunner::Stopwatch', 'collection-runner');
    }

    public function onEnd(EndEvent $event)
    {
        $event = $this->stopwatch->stop('CollectionRunner::Stopwatch');
        $this->output->writeln(sprintf('<info>%s</info>', $event));
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->start(),
            [$this, 'onStart']
        );

        $this->dispatcher->$method(
            $this->eventNames->end(),
            [$this, 'onEnd']
        );
    }
}
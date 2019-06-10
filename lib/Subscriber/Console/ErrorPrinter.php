<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Subscriber\Console;

use Kompakt\CollectionRunner\EventNamesInterface;
use Kompakt\CollectionRunner\Event\EndErrorEvent;
use Kompakt\CollectionRunner\Event\ItemErrorEvent;
use Kompakt\CollectionRunner\Event\PageBeginErrorEvent;
use Kompakt\CollectionRunner\Event\PageDoneErrorEvent;
use Kompakt\CollectionRunner\Event\StartErrorEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ErrorPrinter
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $output = null;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EventNamesInterface $eventNames
    )
    {
        $this->dispatcher = $dispatcher;
        $this->eventNames = $eventNames;
    }

    public function activate(OutputInterface $output)
    {
        $this->output = $output;
        $this->handleListeners(true);
    }

    public function deactivate()
    {
        $this->handleListeners(false);
    }

    public function onStartError(StartErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>Collection Start error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onPageBeginError(PageBeginErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>Collection PageBegin error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onItemError(ItemErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>Collection Item error: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onPageDoneError(PageDoneErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>Collection PageDone error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onEndError(EndErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>Collection End error %s</error>',
                $event->getException()->getMessage()
            )
        );
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
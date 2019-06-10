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
use Kompakt\CollectionRunner\Event\EndEvent;
use Kompakt\CollectionRunner\Event\ItemErrorEvent;
use Kompakt\CollectionRunner\Event\ItemEvent;
use Kompakt\CollectionRunner\Event\PageBeginErrorEvent;
use Kompakt\CollectionRunner\Event\PageBeginEvent;
use Kompakt\CollectionRunner\Event\PageDoneErrorEvent;
use Kompakt\CollectionRunner\Event\PageDoneEvent;
use Kompakt\CollectionRunner\Event\StartErrorEvent;
use Kompakt\CollectionRunner\Event\StartEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Debugger
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
        $this->output->writeln(
            sprintf(
                '<info>COLLECTION DEBUG: Start</info>'
            )
        );
    }

    public function onStartError(StartErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>COLLECTION DEBUG: Start error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onPageBegin(PageBeginEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<info>COLLECTION DEBUG: PageBegin</info>'
            )
        );
    }

    public function onPageBeginError(PageBeginErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>COLLECTION DEBUG: Page Begin error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onItem(ItemEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<info>COLLECTION DEBUG: Item</info>',
                $event->getItem()
            )
        );
    }

    public function onItemError(ItemErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>! DEBUG: Item error: %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onPageDone(PageDoneEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<info>COLLECTION DEBUG: PageDone</info>'
            )
        );
    }

    public function onPageDoneError(PageDoneErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>COLLECTION DEBUG: PageDone error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    public function onEnd(EndEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<info>COLLECTION DEBUG: End</info>'
            )
        );
    }

    public function onEndError(EndErrorEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<error>COLLECTION DEBUG: End error %s</error>',
                $event->getException()->getMessage()
            )
        );
    }

    protected function handleListeners($add)
    {
        $method = ($add) ? 'addListener' : 'removeListener';

        $this->dispatcher->$method(
            $this->eventNames->start(),
            [$this, 'onStart']
        );

        $this->dispatcher->$method(
            $this->eventNames->startError(),
            [$this, 'onStartError']
        );

        $this->dispatcher->$method(
            $this->eventNames->pageBegin(),
            [$this, 'onPageBegin']
        );

        $this->dispatcher->$method(
            $this->eventNames->pageBeginError(),
            [$this, 'onPageBeginError']
        );

        $this->dispatcher->$method(
            $this->eventNames->item(),
            [$this, 'onItem']
        );

        $this->dispatcher->$method(
            $this->eventNames->itemError(),
            [$this, 'onItemError']
        );

        $this->dispatcher->$method(
            $this->eventNames->pageDone(),
            [$this, 'onPageDone']
        );

        $this->dispatcher->$method(
            $this->eventNames->pageDoneError(),
            [$this, 'onPageDoneError']
        );

        $this->dispatcher->$method(
            $this->eventNames->end(),
            [$this, 'onEnd']
        );

        $this->dispatcher->$method(
            $this->eventNames->endError(),
            [$this, 'onEndError']
        );
    }
}
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
use Kompakt\CollectionRunner\Event\PageBeginErrorEvent;
use Kompakt\CollectionRunner\Event\PageDoneErrorEvent;
use Kompakt\CollectionRunner\Event\StartErrorEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SummaryPrinter
{
    protected $dispatcher = null;
    protected $eventNames = null;
    protected $output = null;
    protected $startErrorCount = 0;
    protected $pageBeginErrorCount = 0;
    protected $itemErrorCount = 0;
    protected $pageDoneErrorCount = 0;
    protected $endErrorCount = 0;

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

    public function onStartError(StartErrorEvent $event)
    {
        $this->startErrorCount++;
    }

    public function onPageBeginError(PageBeginErrorEvent $event)
    {
        $this->pageBeginErrorCount++;
    }

    public function onItemError(ItemErrorEvent $event)
    {
        $this->itemErrorCount++;
    }

    public function onPageDoneError(PageDoneErrorEvent $event)
    {
        $this->pageDoneErrorCount++;
    }

    public function onEndError(EndErrorEvent $event)
    {
        $this->endErrorCount++;

        $this->printSummary();
    }

    public function onEnd(EndEvent $event)
    {
        $this->printSummary();
    }

    protected function printSummary()
    {
        if (
            !$this->startErrorCount &&
            !$this->pageBeginErrorCount &&
            !$this->itemErrorCount &&
            !$this->pageDoneErrorCount &&
            !$this->endErrorCount
        )
        {
            return;
        }

        $this->output->writeln(
            sprintf(
                '<comment>%s</comment>',
                $this->getSeparator()
            )
        );

        if ($this->startErrorCount)
        {
            $this->output->writeln(
                sprintf(
                    '<error>= Start errors: %d</error>',
                    $this->startErrorCount
                )
            );
        }

        if ($this->pageBeginErrorCount)
        {
            $this->output->writeln(
                sprintf(
                    '<error>= PageBegin errors: %d</error>',
                    $this->pageBeginErrorCount
                )
            );
        }

        if ($this->itemErrorCount)
        {
            $this->output->writeln(
                sprintf(
                    '<error>= Item errors: %d</error>',
                    $this->itemErrorCount
                )
            );
        }

        if ($this->pageDoneErrorCount)
        {
            $this->output->writeln(
                sprintf(
                    '<error>= PageDone errors: %d</error>',
                    $this->pageDoneErrorCount
                )
            );
        }

        if ($this->endErrorCount)
        {
            $this->output->writeln(
                sprintf(
                    '<error>= End errors: %d</error>',
                    $this->endErrorCount
                )
            );
        }
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

        $this->dispatcher->$method(
            $this->eventNames->end(),
            [$this, 'onEnd']
        );
    }

    protected function getSeparator()
    {
        return '---------------------------------------';
    }
}
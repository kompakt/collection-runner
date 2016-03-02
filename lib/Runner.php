<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner;

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
use Kompakt\CollectionRunner\Exception\RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Runner
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

    public function run($numItems, $getItemsFunction, $numItemsPerPage = 1000)
    {
        try {
            if (!$this->start($numItems))
            {
                $this->end();
                return;
            }

            $numPages = ceil($numItems / $numItemsPerPage);

            for ($i = 0; $i < $numPages; $i++)
            {
                $first = $i * $numItemsPerPage;

                $items = $getItemsFunction(
                    $first,
                    $numItemsPerPage,
                    $numPages,
                    $i + 1
                );

                $pageBeginOk = $this->pageBegin(
                    $items,
                    $first,
                    $numItemsPerPage,
                    $numPages,
                    $i + 1
                );

                if (!$pageBeginOk)
                {
                    continue;
                }

                foreach ($items as $item)
                {
                    if (!$this->item($item))
                    {
                        continue;
                    }
                }

                $this->pageDone(
                    $items,
                    $first,
                    $numItemsPerPage,
                    $numPages,
                    $i + 1
                );
            }

            $this->end();
        }
        catch (\Exception $e)
        {
            throw new RuntimeException(sprintf('List runner error'), null, $e);
        }
    }

    protected function start($numItems)
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->start(),
                new StartEvent($numItems)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->startError(),
                new StartErrorEvent($e, $numItems)
            );

            return false;
        }
    }

    protected function pageBegin($items, $first, $max, $numPages, $currentPage)
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->pageBegin(),
                new PageBeginEvent($items, $first, $max, $numPages, $currentPage)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->pageBeginError(),
                new PageBeginErrorEvent($e, $items, $first, $max, $numPages, $currentPage)
            );

            return false;
        }
    }

    protected function item($item)
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->item(),
                new ItemEvent($item)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->itemError(),
                new ItemErrorEvent($e, $item)
            );

            return false;
        }
    }

    protected function pageDone($items, $first, $max, $numPages, $currentPage)
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->pageDone(),
                new PageDoneEvent($items, $first, $max, $numPages, $currentPage)
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->pageDoneError(),
                new PageDoneErrorEvent($e, $items, $first, $max, $numPages, $currentPage)
            );

            return false;
        }
    }

    protected function end()
    {
        try {
            $this->dispatcher->dispatch(
                $this->eventNames->end(),
                new EndEvent()
            );

            return true;
        }
        catch (\Exception $e)
        {
            $this->dispatcher->dispatch(
                $this->eventNames->endError(),
                new EndErrorEvent($e)
            );

            return false;
        }
    }
}
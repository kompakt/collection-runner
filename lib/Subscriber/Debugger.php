<?php

namespace Kompakt\CollectionRunner\Subscriber;

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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Debugger
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
        $this->writeln(
            sprintf(
                '+ DEBUG: Start'
            )
        );
    }

    public function onStartError(StartErrorEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: Start error %s',
                $event->getException()->getMessage()
            )
        );
    }

    public function onPageBegin(PageBeginEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: PageBegin'
            )
        );
    }

    public function onPageBeginError(PageBeginErrorEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: PageBegin error %s',
                $event->getException()->getMessage()
            )
        );
    }

    public function onItem(ItemEvent $event)
    {
        $this->writeln(
            sprintf(
                '  + DEBUG: Item'
            )
        );
    }

    public function onItemError(ItemErrorEvent $event)
    {
        $this->writeln(
            sprintf(
                '  ! DEBUG: Item error: %s',
                $event->getException()->getMessage()
            )
        );
    }

    public function onPageDone(PageDoneEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: PageDone'
            )
        );
    }

    public function onPageDoneError(PageDoneErrorEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: PageDone error %s',
                $event->getException()->getMessage()
            )
        );
    }

    public function onEnd(EndEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: End'
            )
        );
    }

    public function onEndError(EndErrorEvent $event)
    {
        $this->writeln(
            sprintf(
                '+ DEBUG: End error %s',
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

    protected function writeln($msg)
    {
        echo sprintf("%s\n", $msg);
    }
}
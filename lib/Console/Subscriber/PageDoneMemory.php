<?php

namespace Kompakt\CollectionRunner\Console\Subscriber;

use Kompakt\CollectionRunner\EventNamesInterface;
use Kompakt\CollectionRunner\Event\PageDoneEvent;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PageDoneMemory
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

    public function onPageDone(PageDoneEvent $event)
    {
        $this->output->writeln(
            sprintf(
                '<info>Memory: %s Mb</info>',
                round(memory_get_usage() / 1024 / 1024, 0)
            )
        );
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
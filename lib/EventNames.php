<?php

namespace Kompakt\CollectionRunner;

use Kompakt\CollectionRunner\EventNamesInterface;

class EventNames implements EventNamesInterface
{
    protected $namespace = null;

    public function __construct($namespace = 'collection_runner')
    {
        $this->namespace = $namespace;
    }

    public function start()
    {
        return sprintf('%s.start', $this->namespace);
    }

    public function startError()
    {
        return sprintf('%s.start_error', $this->namespace);
    }

    public function pageBegin()
    {
        return sprintf('%s.page_begin', $this->namespace);
    }

    public function pageBeginError()
    {
        return sprintf('%s.page_begin_error', $this->namespace);
    }

    public function item()
    {
        return sprintf('%s.item', $this->namespace);
    }

    public function itemError()
    {
        return sprintf('%s.item_error', $this->namespace);
    }

    public function pageDone()
    {
        return sprintf('%s.page_done', $this->namespace);
    }

    public function pageDoneError()
    {
        return sprintf('%s.page_done_error', $this->namespace);
    }

    public function end()
    {
        return sprintf('%s.end', $this->namespace);
    }

    public function endError()
    {
        return sprintf('%s.end_error', $this->namespace);
    }
}
<?php

namespace Kompakt\CollectionRunner\Event;

use Symfony\Component\EventDispatcher\Event;

class StartErrorEvent extends Event
{
    protected $exception = null;
    protected $numItems = null;

    public function __construct(\Exception $exception, $numItems)
    {
        $this->exception = $exception;
        $this->numItems = $numItems;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getNumItems()
    {
        return $this->numItems;
    }
}
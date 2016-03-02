<?php

namespace Kompakt\CollectionRunner\Event;

use Symfony\Component\EventDispatcher\Event;

class StartEvent extends Event
{
    protected $numItems = null;

    public function __construct($numItems)
    {
        $this->numItems = $numItems;
    }

    public function getNumItems()
    {
        return $this->numItems;
    }
}
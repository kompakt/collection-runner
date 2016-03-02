<?php

namespace Kompakt\CollectionRunner\Event;

use Symfony\Component\EventDispatcher\Event;

class ItemEvent extends Event
{
    protected $item = null;

    public function __construct($item)
    {
        $this->item = $item;
    }

    public function getItem()
    {
        return $this->item;
    }
}
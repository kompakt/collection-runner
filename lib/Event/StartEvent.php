<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Event;

use Symfony\Contracts\EventDispatcher\Event;

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
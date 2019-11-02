<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ItemErrorEvent extends Event
{
    protected $exception = null;
    protected $item = null;

    public function __construct(\Exception $exception, $item)
    {
        $this->exception = $exception;
        $this->item = $item;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getItem()
    {
        return $this->item;
    }
}
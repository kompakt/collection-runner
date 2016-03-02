<?php

namespace Kompakt\CollectionRunner\Event;

use Symfony\Component\EventDispatcher\Event;

class EndErrorEvent extends Event
{
    protected $exception = null;

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    public function getException()
    {
        return $this->exception;
    }
}
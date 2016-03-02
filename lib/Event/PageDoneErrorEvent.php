<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Event;

use Symfony\Component\EventDispatcher\Event;

class PageDoneErrorEvent extends Event
{
    protected $exception = null;
    protected $items = null;
    protected $first = null;
    protected $max = null;
    protected $numPages = null;
    protected $currentPage = null;

    public function __construct(\Exception $exception, $items, $first, $max, $numPages, $currentPage)
    {
        $this->exception = $exception;
        $this->items = $items;
        $this->first = $first;
        $this->max = $max;
        $this->numPages = $numPages;
        $this->currentPage = $currentPage;
    }

    public function getException()
    {
        return $this->exception;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getFirst()
    {
        return $this->first;
    }

    public function getMax()
    {
        return $this->max;
    }

    public function getNumPages()
    {
        return $this->numPages;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }
}
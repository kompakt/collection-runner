<?php

namespace Kompakt\CollectionRunner;

interface EventNamesInterface
{
    public function start();
    public function startError();
    public function pageBegin();
    public function pageBeginError();
    public function item();
    public function itemError();
    public function pageDone();
    public function pageDoneError();
    public function end();
    public function endError();
}
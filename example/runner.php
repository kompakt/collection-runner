<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

use Kompakt\CollectionRunner\Console\Subscriber\Debugger;
use Kompakt\CollectionRunner\Runner;
use Kompakt\CollectionRunner\EventNames;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher;

require sprintf('%s/bootstrap.php', __DIR__);

$dispatcher = new EventDispatcher();
$eventNames = new EventNames();
$runner = new Runner($dispatcher, $eventNames);
$debugger = new Debugger($dispatcher, $eventNames);
$debugger->activate(new ConsoleOutput());

$countries = [
    'Andorra',
    'Austria',
    'Australia',
    'Brazil',
    'Chile',
    'Cuba',
    'Germany',
    'Finland',
    'France',
    'Guatemala',
    'Italy',
    'Lebanon',
    'Morocco',
    'Nepal',
    'Russia',
    'Switzerland',
    'Thailand'
];

$getPaginatedCountries = function($first, $max) use ($countries)
{
    return array_slice($countries, $first, $max);
};

$runner->run(count($countries), $getPaginatedCountries, 5);


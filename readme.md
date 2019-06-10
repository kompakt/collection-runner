# Kompakt\CollectionRunner

Little helper to run through a collection of things with event-emitting along the way.

## Install

Through Composer:

+ `composer require kompakt/collection-runner`

## Example

Step through a list in portions (pages) of 5 items

```php
use Kompakt\CollectionRunner\Console\Subscriber\Debugger;
use Kompakt\CollectionRunner\Runner;
use Kompakt\CollectionRunner\EventNames;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcher;

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

$getCountriesCallable = function($first, $max) use ($countries)
{
    return array_slice($countries, $first, $max);
};

$runner->run(count($countries), $getCountriesCallable, 5);
```

## License

kompakt/collection-runner is licensed under the MIT license - see the LICENSE file for details
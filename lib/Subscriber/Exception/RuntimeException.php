<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Subscriber\Exception;

use Kompakt\CollectionRunner\Exception as BaseException;

class RuntimeException extends \RuntimeException implements BaseException
{}
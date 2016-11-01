<?php

/*
 * This file is part of the kompakt/collection-runner package.
 *
 * (c) Christian Hoegl <chrigu@sirprize.me>
 *
 */

namespace Kompakt\CollectionRunner\Util;

class Counter
{
    protected $stacks = array();

    public function add($name, $id)
    {
        foreach ($this->stacks as $k => $v)
        {
            if (array_key_exists($id, $this->stacks[$k]))
            {
                // any given id may only exist once across all stacks
                unset($this->stacks[$k][$id]);
            }
        }

        $this->stacks[$name][$id] = 1;
        return $this;
    }

    public function count($name)
    {
        if (array_key_exists($name, $this->stacks))
        {
            return count($this->stacks[$name]);
        }

        return 0;
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->stacks as $name => $id)
        {
            $total += count($this->stacks[$name]);
        }

        return $total;
    }

    public function getStacks()
    {
        $stacks = array();

        foreach ($this->stacks as $name => $id)
        {
            $stacks[$name] = count($this->stacks[$name]);
        }

        return $stacks;
    }
}
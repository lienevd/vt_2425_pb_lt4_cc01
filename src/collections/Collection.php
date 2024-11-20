<?php

namespace Src\Collections;

class Collection extends AbstractCollection
{
    protected function createInstance(): self
    {
        return $this;
    }
}

<?php

namespace Src\Collections;

class Collection extends AbstractCollection
{
    protected function createInstance(?array $items): self
    {
        return new self($items);
    }
}

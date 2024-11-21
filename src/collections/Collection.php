<?php

namespace Src\Collections;

class Collection extends AbstractCollection
{
    protected function getInstance(): self
    {
        return $this;
    }
}

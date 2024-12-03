<?php

namespace Src\Collections;

use Src\Enums\PHPTypes;

class HintCollection extends AbstractCollection
{
    protected function configure(): void
    {
        $this->setStructure([
            'id' => ['type' => PHPTypes::INT],
            'hintText' => ['type' => PHPTypes::STR],
            'category' => ['type' => PHPTypes::STR]
        ]);
    }

    protected function getInstance(): self
    {
        return $this;
    }
}
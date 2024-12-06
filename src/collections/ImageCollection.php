<?php

namespace Src\Collections;

use Src\Enums\PHPTypes;

class ImageCollection extends AbstractCollection
{
    protected function configure(): void
    {
        $this->setStructure([
            'id' => ['type' => PHPTypes::INT],
            'image' => ['type' => PHPTypes::STR],
            'category' => ['type' => PHPTypes::STR],
            'category_id' => ['type' => PHPTypes::INT]
        ]);
    }

    protected function getInstance(): self
    {
        return $this;
    }
}

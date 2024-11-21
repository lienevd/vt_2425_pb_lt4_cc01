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
            'category' => ['type' => PHPTypes::STR]
        ]);
        $this->addModifier('image', function (string $image) {
            return '<img src="data:jpg;base64,' . $image . '" alt="Image">';
        });
    }

    protected function getInstance(): self
    {
        return $this;
    }
}

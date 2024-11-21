<?php

namespace Src\Collections;

use Src\Enums\PHPTypes;

use function PHPSTORM_META\map;

class Collection extends AbstractCollection
{
    /**
     * @return \Src\Collections\Collection
     */
    protected function getInstance(): self
    {
        return $this;
    }

    /**
     * @override
     */
    public function map(array $jumbled): self
    {
        $structure = [];
        foreach ($jumbled as $item) {
            foreach ($item as $name => $value) {
                $type = PHPTypes::MIXED;
                $valueType = gettype($value);
                if ($valueType !== 'null') {
                    $type = PHPTypes::from($valueType);
                }

                $structure[$name] = ['type' => $type];
            }
        }
        $this->setStructure($structure);

        $this->items = $jumbled;

        return $this;
    }
}

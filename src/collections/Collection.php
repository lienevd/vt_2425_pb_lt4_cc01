<?php

namespace Src\Collections;

class Collection implements \IteratorAggregate
{
    private ?array $structure = null;

    public function __construct(
        private ?array $items = null
    ) {
        $this->validateItems();
    }

    /**
     * Validates the items based on the provided structure.
     *
     * @return void
     * @throws \InvalidArgumentException if validation fails
     */
    private function validateItems(): void
    {
        if ($this->items === null || $this->structure === null) {
            return;
        }

        foreach ($this->items as $name => $value) {
            $this->validateItem($name, $value);
        }
    }

    /**
     * Validates a single item based on its name and type.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     * @throws \InvalidArgumentException if validation fails
     */
    private function validateItem(string $name, $value): void
    {
        $type = $this->structure[$name];

        if (class_exists($type)) {
            if (!($value instanceof $type)) {
                throw new \InvalidArgumentException("Value for '$name' must be an instance of $type.");
            }
            return;
        }
        $types = ['string', 'int', 'float', 'bool', 'array', 'object', 'null'];
        if (in_array($type, $types, true)) {
            settype($value, $type);
            if (gettype($value) !== $type) {
                throw new \InvalidArgumentException("Value for '$name' must be of type $type.");
            }
            return;
        }

        throw new \InvalidArgumentException("Invalid type '$type' for '$name'.");
    }

    /**
     * Sets the structure for the collection.
     *
     * @param array<string, string> $structure
     * @return void
     */
    protected function setStructure(array $structure): void
    {
        $this->structure = $structure;
    }

    public function map(array $fromDB): self
    {
        $items = [];

        if ($this->structure !== null) {
            foreach ($fromDB as $name => $value) {
                if (!array_key_exists($name, $this->structure)) {
                    throw new \Exception("Invalid array error does not match structure $name is not in structure");
                }
                $items[$name] = $value;
            }
        }

        return new self($items);
    }

    /**
     * Returns an iterator for the collection items.
     *
     * @return \Traversable
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->items ?? []);
    }
}

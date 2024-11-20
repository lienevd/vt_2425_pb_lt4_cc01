<?php

namespace Src\Collections;

abstract class AbstractCollection implements \IteratorAggregate
{
    protected const INT = 'integer';
    protected const STR = 'string';
    protected const FLOAT = 'float';
    protected const BOOL = 'boolean';
    protected const ARRAY = 'array';
    protected const OBJ = 'object';
    protected const NULL = 'null';
    private ?array $structure = null;
    protected ?array $modifiers = null;

    public function __construct(
        protected ?array $items = null,
        protected ?array $options = null
    ) {
        $this->configure();
        $this->validateItems();
        $this->modify();

        $this->handleOptions();
    }

    /**
     * @return array|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setOption(string $name, mixed $value): void
    {
        $this->options[$name] = $value;
    }

    /**
     * @return array
     */
    protected function getNormalizedItems(): array
    {
        if (isset($this->options['single_array']) && $this->options['single_array']) {
            return [$this->items];
        }

        return $this->items;
    }

    private function handleOptions(): void
    {
        $options = $this->options;
        if ($options === null) {
            return;
        }

        if (isset($options['single_array']) && $options['single_array']) {
            if (count($this->items) === 1) {
                foreach ($this->items as $item) {
                    $this->items = $item;
                }
            }
        }
    }

    protected function configure(): void {}

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

        foreach ($this->items as $item) {
            $this->validateItem($item);
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
    private function validateItem(array $item): void
    {
        foreach ($item as $name => $value) {
            if (!array_key_exists($name, $this->structure)) {
                throw new \Exception("Invalid item key: $name");
            }

            $type = $this->structure[$name]['type'];
            $size = $this->structure[$name]['size'] ?? null;

            if (class_exists($type)) {
                if (!($value instanceof $type)) {
                    throw new \InvalidArgumentException("Value for '$name' must be an instance of $type.");
                }
                continue;
            }

            $normalizedTypes = [
                'int' => 'integer',
                'bool' => 'boolean',
                'float' => 'double',
                'string' => 'string',
                'array' => 'array',
                'object' => 'object',
                'null' => 'NULL'
            ];

            $expectedType = $normalizedTypes[$type] ?? $type;

            if (gettype($value) !== $expectedType) {
                throw new \InvalidArgumentException("Value for '$name' must be of type $type. Provided type: " . gettype($value));
            }

            if ($size === null) {
                return;
            }

            $isValid = match ($type) {
                'string' => strlen($value) <= $size,
                'int', 'float' => abs($value) <= $size,
                default => true,
            };

            if (!$isValid) {
                $typeDescription = $type === 'string' ? 'length' : 'size';
                throw new \InvalidArgumentException("Value for '$name' exceeds the allowed $typeDescription of $size.");
            }
        }
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

    public function normalize(array $item): array
    {
        $newItem = [];
        foreach ($this->structure as $name => $settings) {
            $itemValue = $item[$name];
            $newItem[$name] = $itemValue;
        }

        return $newItem;
    }

    /**
     * @param array[] $jumbled
     * @return \Src\Collections\AbstractCollection
     */
    public function map(array $jumbled): self
    {
        $items = [];

        foreach ($jumbled as $jumble) {
            $item = [];

            foreach ($jumble as $name => $value) {
                if (!array_key_exists($name, $this->structure)) {
                    throw new \Exception("Invalid item key: $name");
                }

                foreach ($this->structure as $structureName => $type) {
                    if ($structureName === $name) {
                        $item[$structureName] = $value;
                    }
                }
            }

            $items[] = $item;
        }

        $this->items = $items;
        $this->validateItems();
        $this->handleOptions();

        return $this->createInstance();
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

    /**
     * @param callable $callable
     * @return array|null
     */
    public function filter(callable $callable): ?array
    {
        if ($this->items === null) {
            return null;
        }

        $filteredItems = array_filter($this->items, $callable, ARRAY_FILTER_USE_BOTH);

        return empty($filteredItems) ? null : $filteredItems;
    }

    /**
     * @param array $item
     * @return void
     */
    public function add(array $item): void
    {
        $this->validateItem($item);

        if (isset($this->options['single_array']) && $this->options['single_array']) {
            $this->items = [$this->items];
        }

        $item = $this->normalize($item);
        $this->items[] = $item;
    }

    /**
     * @param string $name
     * @param callable $modifier
     * @param bool $activeByDefault
     * @throws \Exception
     * @return void
     */
    protected function addModifier(string $name, callable $modifier, bool $activeByDefault = true): void
    {
        if (!array_key_exists($name, $this->structure)) {
            throw new \Exception("Invalid item key: $name");
        }

        $this->modifiers[$name] = [
            'modifier' => $modifier,
            'active' => $activeByDefault
        ];
    }

    /**
     * @param string $name
     * @return void
     */
    public function activateModifier(string $name): self
    {
        $this->modifiers[$name]['active'] = true;
        return $this->createInstance();
    }

    /**
     * @param string $name
     * @return \Src\Collections\AbstractCollection
     */
    public function deactivateModifier(string $name): self
    {
        $this->modifiers[$name]['active'] = false;

        return $this->createInstance();
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function modify(): void
    {
        if ($this->items === null) {
            return;
        }
        foreach ($this->items as &$item) {
            foreach ($item as $name => &$value) {
                if (!isset($this->modifiers[$name])) {
                    continue;
                }

                $active = $this->modifiers[$name]['active'];
                if (!$active) {
                    continue;
                }
                $modifier = $this->modifiers[$name]['modifier'];
                $arguments = [$name => $value];
                $value = call_user_func_array($modifier, $arguments);
            }
        }
    }

    /**
     * Abstract method to create an instance of the concrete subclass.
     *
     * @param array|null $items
     * @return static
     */
    abstract protected function createInstance(): self;
}

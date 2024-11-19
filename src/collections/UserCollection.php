<?php

namespace Src\Collections;

class UserCollection extends AbstractCollection
{
    protected function configure(): void
    {
        $this->setStructure([
            'id' => self::INT,
            'username' => self::STR,
            'password' => self::STR
        ]);
        $this->addModifier('password', function (string $password): string {
            return password_hash($password, PASSWORD_BCRYPT);
        });
    }

    /**
     * @param array|null $items
     * @return \Src\Collections\UserCollection
     */
    protected function createInstance(): self
    {
        return $this;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id): ?array
    {
        foreach ($this->items as $item) {
            if ($item['id'] === $id) {
                return $item;
            }
        }

        return null;
    }

    /**
     * @param string $username
     * @return mixed
     */
    public function getByUsername(string $username): ?array
    {
        foreach ($this->items as $item) {
            if ($item['username'] === $username) {
                return $item['username'];
            }
        }

        return null;
    }
}

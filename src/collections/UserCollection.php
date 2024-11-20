<?php

namespace Src\Collections;

class UserCollection extends AbstractCollection
{
    /**
     * @throws \Exception
     * @return void
     */
    protected function configure(): void
    {
        $this->setStructure([
            'id' => ['type' => self::INT],
            'username' => ['type', self::STR, 'size' => 20],
            'password' => ['type' => self::STR, 'size' => 256]
        ]);
        $this->addModifier('password', function (string $password): string {
            return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
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
        $items = $this->getNormalizedItems();
        foreach ($items as $item) {
            if ($item['username'] === $username) {
                $passwordHash = $item['password'];
                $item['password'] = function (string $password) use ($passwordHash): bool {
                    return password_verify($password, $passwordHash);
                };
                return $item;
            }
        }

        return null;
    }

    public function validatePwdGetUser(string $password): ?array
    {
        if (!$this->modifiers['password']['active']) {
            return null;
        }

        foreach ($this->items as $item) {
            if (password_verify($password, $item['password'])) {
                return $item;
            }
        }

        return null;
    }
}

<?php

namespace Src\Database;

use PDO;
use PDOException;
use PDOStatement;
use Src\Collections\AbstractCollection;
use Src\Collections\Collection;

class DB
{
    protected $connection;
    protected bool|PDOStatement $statement;

    public function __construct(
        private bool $testing = false
    ) {
        $this->connect();
    }

    /**
     * @return void
     */
    private function connect(): void
    {
        if (!$this->testing) {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8';
            $username = DB_USER;
            $password = DB_PASS;
        } else {
            $dsn = 'mysql:host=' . DB_HOST_TEST . ';port=' . DB_PORT_TEST . ';dbname=' . DB_NAME_TEST . ';charset=utf8';
            $username = DB_USER_TEST;
            $password = DB_PASS_TEST;
        }

        try {
            $this->connection = new PDO($dsn, $username, $password);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    /**
     * @return \PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * @param string $query
     * @return \Src\Database\DB
     */
    public function query(string $query): self
    {
        $this->statement = $this->connection->prepare($query);

        return $this;
    }

    /**
     * array(
     *  [placeholder, value, PDO type],
     * )
     * @param array $data
     * @return \Src\Database\DB
     */
    public function bindParams(array $data): self
    {
        foreach ($data as $i) {
            $this->statement->bindParam($i[0], $i[1], $i[2]);
        }

        return $this;
    }

    /**
     * @throws \PDOException
     * @return bool
     */
    public function execute(): bool
    {
        return $this->statement->execute();
    }

    /**
     * @throws \PDOException
     * @return null|AbstractCollection
     */
    public function fetchAssoc(?AbstractCollection $collection = null): ?AbstractCollection
    {
        $this->execute();
        $results = $this->statement->fetchAll(PDO::FETCH_ASSOC);
        if (empty($results)) {
            return null;
        }

        return $this->mapToCollection($collection, $results);
    }

    /**
     * @throws \PDOException
     * @return null|AbstractCollection
     */
    public function fetchSingle(?AbstractCollection $collection = null): ?AbstractCollection
    {
        $this->execute();
        $results = $this->statement->fetch(PDO::FETCH_ASSOC);
        if ($results === null) {
            return null;
        }
        return $this->mapToCollection($collection, [$results]);
    }

    /**
     * @param \Src\Collections\AbstractCollection|null $collection
     * @param array $items
     * @return \Src\Collections\Collection
     */
    private function mapToCollection(AbstractCollection $collection = null, array $items): AbstractCollection
    {
        if ($collection === null) {
            return new Collection($items);
        }
        $collection->setOption('array_single', true);
        return $collection->map($items);
    }

    /**
     * @return int
     */
    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    public function getLastInsertId(): int
    {
        return $this->connection->lastInsertId();
    }
}

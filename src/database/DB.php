<?php

namespace Src\Database;

use PDO;
use PDOException;
use PDOStatement;

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
     * @return bool
     */
    public function execute(): bool
    {
        try {
            return $this->statement->execute();
        } catch (PDOException $e) {
            echo 'Execution failed: ' . $e->getMessage();
        }

        return false;
    }

    /**
     * @return array
     */
    public function fetchAssoc(): array
    {
        $this->execute();
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array
     */
    public function fetchSingle(): array
    {
        $this->execute();
        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @return int
     */
    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }
}

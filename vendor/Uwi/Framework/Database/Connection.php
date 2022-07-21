<?php

namespace Uwi\Database;

use PDO;
use Uwi\Contracts\SingletonContract;

class Connection implements SingletonContract
{
    /**
     * PDO connection object
     *
     * @var PDO
     */
    private PDO $connection;

    /**
     * Calls when singleton has been instantiated and saved
     *
     * @return void
     */
    public function boot(): void
    {
        $this->connection = new PDO(
            'mysql:host=' . config('database.connection.host') .
                ';dbname=' . config('database.connection.dbname'),
            config('database.connection.user'),
            config('database.connection.password')
        );
    }

    /**
     * Get the PDO connection
     *
     * @return PDO
     */
    public function getConnection(): PDO
    {
        return $this->connection;
    }

    /**
     * Returns id of last insterted row
     *
     * @return integer|null
     */
    public function lastInsertedID(): ?int
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Exec a query
     *
     * @return array
     */
    public function exec(string $query, array $args = []): array
    {
        $st = $this->connection->prepare($query);
        $st->execute($args);
        $result = $st->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }
}

<?php

namespace Uwi\Services\Database;

use Uwi\Contracts\Database\ConnectionContract;

class Connection implements ConnectionContract
{
    /**
     * PDO connection object
     *
     * @var \PDO
     */
    private \PDO $connection;

    /**
     * Instantiate Connection.
     */
    public function __construct()
    {
        // Credentials for testing in the Docker environment only...
        $this->connection = new \PDO(
            sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                env('DATABASE_HOST'),
                env('DATABASE_PORT'),
                env('DATABASE_NAME'),
                env('DATABASE_CHARSET')
            ),
            env('DATABASE_USER'),
            env('DATABASE_PASSWORD')
        );
    }

    /**
     * Returns ID of last insterted row.
     *
     * @return integer|null
     */
    public function lastInsertedId(): int|null
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Exec a query.
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

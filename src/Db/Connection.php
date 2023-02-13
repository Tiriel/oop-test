<?php

namespace App\Db;

class Connection
{

    private \PDO $_connection;
    private bool $transactional = false;

    public static Connection $_self;

    public function __construct(array $config)
    {
        if (!array_key_exists('platform', $config)) {
            throw new \InvalidArgumentException(sprintf("%s needs and array with proper platform information to work.", static::class));
        }

        ['platform' => $platform, 'host' => $host, 'port' => $port, 'user' => $user, 'password' => $password, 'dbName' => $dbName] = $config;

        $dsn = "$platform:host=$host;port=$port;dbname=$dbName";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_CLASS,
        ];

        try {
            $this->_connection = new \PDO($dsn, $user, $password, $options);
        } catch (\PDOException $e) {
            if (1049 === $e->getCode()) {
                try {
                    $pdo = new \PDO("$platform:host=$host;port=$port", $user, $password, $options);
                    $pdo->exec("CREATE DATABASE `$dbName`");
                    $pdo = null;
                } catch (\PDOException $e) {
                    var_dump($e->getMessage());
                }
                $this->_connection = new \PDO($dsn, $user, $password, $options);
            }
            throw $e;
        }
    }

    private function __clone(): void {}

    public static function create(array $config = []): static
    {
        return static::$_self ?? static::$_self = new static($config);
    }

    public function isTransactional(): bool
    {
        return $this->transactional;
    }

    public function transaction(): bool
    {
        try {
            $this->transactional = true;

            return $this->_connection->beginTransaction();
        } catch (\PDOException $e) {
            $this->transactional = false;

            throw $e;
        }
    }

    public function commit(): bool
    {
        try {
            $result = $this->_connection->commit();
        } finally {
            $this->transactional = false;

            return $result ?? false;
        }
    }

    public function rollback(): bool
    {
        try {
            $result = $this->_connection->rollBack();
        } finally {
            $this->transactional = false;

            return $result ?? false;
        }
    }

    public function prepare(string $query): \PDOStatement
    {
        return $this->_connection->prepare($query);
    }

    public function execute(\PDOStatement $statement): bool
    {
        $this->transaction();

        try {
            $result = $statement->execute();
        } catch (\PDOException $e) {
            $this->rollback();

            throw $e;
        }

        if ($result) {
            $this->commit();

            return true;
        }
        $this->rollback();

        return false;
    }

    public function batchTransaction(string $query, array $params): bool
    {
        $this->transaction();

        $statement = $this->prepare($query);

        try {
            $result = $this->bindBatchParams($statement, $params);
        } catch (\PDOException $e) {
            $this->rollback();

            throw $e;
        }

        if ($result) {
            $this->commit();

            return true;
        }
        $this->rollback();

        return false;
    }

    public function lastInsertId(?string $name = null): string
    {
        return $this->_connection->lastInsertId($name);
    }

    private function bindBatchParams(\PDOStatement $statement, array $params): bool
    {
        foreach ($params as $index => $param) {
            if (is_array($param)) {
                foreach ($param as $key => $value) {
                    $key = is_int($key) ? $key + 1 : $key;
                    $statement->bindParam($key, $value);
                    $statement->execute();
                }

                return true;
            }

            $index = is_int($index) ? $index + 1 : $index;
            $statement->bindParam($index, $param);

            return $statement->execute();
        }

        return true;
    }
}

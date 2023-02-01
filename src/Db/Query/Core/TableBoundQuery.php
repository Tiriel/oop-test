<?php

namespace App\Db\Query\Core;

use App\Db\Connection;

abstract class TableBoundQuery extends Query
{
    protected mixed $alias;
    protected mixed $shortName;

    public function __construct(
        Connection $db,
        protected readonly string $fqcn
    ) {
        parent::__construct($db);
        [$this->shortName, $this->alias] = $this->getTableNameAndAliasFromClassName($fqcn);
    }

    protected function fetchQuery(array $where = [], array $orderBy = [], int $limit = 0, int $offset = 0): bool|array
    {
        $statement = $this->select([$this->shortName, $this->alias], '*', [], $where, $orderBy, $limit, $offset);

        return $statement->fetchAll(\PDO::FETCH_CLASS, $this->fqcn);
    }

    protected function executeQuery(array $values, array $where = [], array $orderBy = [], int $limit = 0, int $offset = 0): bool
    {
        if (\count($where) > 0) {
            return $this->update($this->shortName, $values, $where, $orderBy, $limit, $offset);
        }

        return $this->insert($this->shortName, '', $values);
    }
}

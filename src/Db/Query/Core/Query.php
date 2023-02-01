<?php

namespace App\Db\Query\Core;

use App\Db\Connection;
use PDOStatement;

class Query
{
    public function __construct(
        protected readonly Connection $db
    ) {}

    /**
     * @param array $tableAndAlias
     * @param string|array $fields
     * @param array[] $joins as [['table2', 'table2.fk', 'table1.col', {'inner|left|right'], ...}]
     * @param array $where as [['col', 'operator', 'value', {'and|or'], ...}]
     * @param array $orderBy as ['DESC|ASC', ['col1'{, col2, ...}]] or ['DESC|ASC', 'col']
     * @param int $limit
     * @param int $offset
     * @return PDOStatement
     */
    public function select(
        array $tableAndAlias,
        string|array $fields = '*',
        array $joins = [],
        array $where = [],
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0
    ): PDOStatement
    {
        $fields = is_array($fields) ? implode(',', $fields) : $fields;
        [$table, $alias] = $tableAndAlias;

        $query = "SELECT $fields FROM $table AS $alias ";

        return $this->buildQuery($query, $alias, $joins, $where, $orderBy, $limit, $offset);
    }

    public function insert(
        string $table,
        array|string $fields = '',
        array $values = [],
        array $where = [],
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0
    ): bool
    {
        $fields = is_array($fields) ? '('.implode(',', $fields).')' : $fields;
        $values = implode(',', $values);

        $query = "INSERT INTO $table $fields VALUES ({$values}) ";

        return $this->db->execute($this->buildQuery($query, '', [], $where, $orderBy, $limit, $offset));
    }

    public function update(
        string $table,
        array $updates = [],
        array $where = [],
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0
    ): bool
    {
        $query = "UPDATE $table SET ";

        $keys = array_keys($updates);
        $last = end($keys);
        foreach ($updates as $col => $update) {
            $query .= "$col = $update";
            $query .= $col === $last ? '' : ', ';
        }

        return $this->db->execute($this->buildQuery($query, '', [], $where, $orderBy, $limit, $offset));

    }

    public function delete(string $table, array $where, array $orderBy = [], int $limit = 0, int $offset = 0): bool
    {
        if ($where === []) {
            throw new \InvalidArgumentException(sprintf("%s::%s needs at least one clause in its \$where argument.", __CLASS__, __METHOD__));
        }

        $query = "DELETE FROM $table ";

        return $this->db->execute($this->buildQuery($query, '', [], $where, $orderBy, $limit, $offset));
    }

    protected function buildQuery(
        string $baseQuery,
        string $alias = '',
        array $joins = [],
        array $where = [],
        array $orderBy = [],
        int $limit = 0,
        int $offset = 0
    ): PDOStatement
    {
        if (\count($joins) > 0) {
            $baseQuery .= $this->getJoinClause($joins, $alias);
        }

        if (\count($where) > 0) {
            $baseQuery .= $this->getWhereClause($where);
        }

        if (\count($orderBy) > 0) {
            $baseQuery .= $this->getOrderByClause($orderBy);
        }

        if ($limit > 0) {
            $baseQuery .= "LIMIT $limit\n";
        }

        if ($offset > 0) {
            $baseQuery .= "OFFSET $offset\n";
        }

        $statement = $this->db->prepare($baseQuery);
        if (\count($where)) {
            foreach ($where as $param) {
                [$col, $value] = $param;
                $statement->bindParam($col, $value);
            }
        }

        return $statement;

    }

    protected function getJoinClause(array $joins, string $originAlias): string
    {
        $clause = '';

        foreach ($joins as $join) {
            // default to inner join type if not provided
            [$joinTable, $joinFk, $originCol, $joinType] = $join + ['', '', '', 'inner'];
            $alias  = substr($joinTable, 0, 1);
            $clause .= "$joinType JOIN $joinTable $alias ON $originAlias.$originCol = $alias.$joinFk\n";
        }

        return $clause;
    }

    protected function getWhereClause(array &$wheres): string
    {
        $clause = '';

        foreach ($wheres as $key => $where) {
            // default to AND WHERE if not provided
            [$col, $operator, $value, $andOr] = $where + ['', '', '', 'and'];
            if ($clause !== '') {
                $clause .= $andOr . ' ';
            }
            $clause .= "WHERE $col $operator :$col\n";
            $wheres[$key] = [":$col", $value];
        }

        return $clause;
    }

    protected function getOrderByClause(array $orderBy): string
    {
        [$direction, $cols] = $orderBy;
        $cols = \is_array($cols) ? implode(',', $cols) : $cols;

        return "ORDER BY $cols $direction\n";
    }

    protected function getTableNameAndAliasFromClassName(string $className): array
    {
        $classParts = explode('\\', $className);
        $shortName = strtolower(array_pop($classParts));

        return [$shortName, substr($shortName, 0, 1)];
    }
}

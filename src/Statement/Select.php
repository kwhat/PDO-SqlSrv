<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\SqlSrv\Statement;

use FaaPz\PDO;
use FaaPz\PDO\DatabaseException;
use FaaPz\PDO\QueryInterface;
use FaaPz\PDO\SqlSrv\Clause;
use FaaPz\PDO\SqlSrv\Database;
use FaaPz\PDO\Statement\Call;

/**
 * @property string|array<string, string|Call|Select>|null $table
 * @property array<int|string, string> $columns
 * @property bool $distinct
 * @property array<int, Call|Select> $union
 * @property array<int, string> $groupBy
 * @property PDO\Clause\Conditional|null $having
 *
 * @property PDO\Clause\Join[] $join
 * @property PDO\Clause\Conditional $where
 * @property string[] $orderBy
 * @property PDO\Clause\Limit $limit
 */
class Select extends PDO\Statement\Select
{
    /**
     * Constructor.
     *
     * @param Database $dbh
     * @param array    $columns
     */
    public function __construct(Database $dbh, array $columns = ['*'])
    {
        parent::__construct($dbh, $columns);
    }

    /**
     * @throws DatabaseException
     *
     * @return string
     */
    public function __toString(): string
    {
        if (empty($this->table)) {
            throw new DatabaseException('No table is set for selection');
        }

        $sql = 'SELECT';
        if ($this->distinct) {
            $sql .= ' DISTINCT';
        }

        if ($this->limit instanceof Clause\Top) {
            $sql .= " {$this->limit}";
        }

        $sql .= " {$this->getColumns()}";

        if (is_array($this->table)) {
            reset($this->table);
            $alias = key($this->table);

            if ($this->table[$alias] instanceof QueryInterface) {
                $table = "({$this->table[$alias]})";
            } else {
                $table = $this->table[$alias];
            }

            if (is_string($alias)) {
                $table .= " AS {$alias}";
            }
        } else {
            $table = "{$this->table}";
        }
        $sql .= " FROM {$table}";

        if (!empty($this->join)) {
            $sql .= ' ' . implode(' ', $this->join);
        }

        if ($this->where != null) {
            $sql .= " WHERE {$this->where}";
        }

        if (!empty($this->groupBy)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }

        if ($this->having != null) {
            $sql .= " HAVING {$this->having}";
        }

        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ';
            foreach ($this->orderBy as $column => $direction) {
                $sql .= "{$column} {$direction}, ";
            }
            $sql = substr($sql, 0, -2);

            if ($this->limit != null && $this->limit instanceof Clause\Offset) {
                $sql .= " {$this->limit}";
            }
        }

        if (!empty($this->union)) {
            $sql = "({$sql}";
            foreach ($this->union as $union) {
                $sql .= ") UNION ({$union}";
            }
            $sql .= ')';
        }

        return $sql;
    }
}

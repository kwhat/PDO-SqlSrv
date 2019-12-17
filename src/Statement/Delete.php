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
class Delete extends PDO\Statement\Delete
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
            trigger_error('No table is set for delete statement', E_USER_ERROR);
        }

        $sql = 'DELETE';
        if ($this->limit instanceof Clause\Top) {
            $sql .= " {$this->limit}";
        }

        if (is_array($this->table)) {
            reset($this->table);
            $alias = key($this->table);

            $table = $this->table[$alias];
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

        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ';
            foreach ($this->orderBy as $column => $direction) {
                $sql .= "{$column} {$direction}, ";
            }
            $sql = substr($sql, 0, -2);
        }

        return $sql;
    }
}

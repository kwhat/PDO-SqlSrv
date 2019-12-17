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
class Update extends PDO\Statement\Update
{
    /**
     * Constructor.
     *
     * @param Database             $dbh
     * @param array<string, mixed> $pairs
     */
    public function __construct(Database $dbh, array $pairs = [])
    {
        parent::__construct($dbh, $pairs);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        if (!isset($this->table)) {
            trigger_error('No table is set for update statement', E_USER_ERROR);
        }

        if (empty($this->pairs)) {
            trigger_error('Missing columns and values for update statement', E_USER_ERROR);
        }

        $sql = "UPDATE {$this->table}";
        if (!empty($this->join)) {
            $sql .= ' ' . implode(' ', $this->join);
        }

        $sql .= " SET {$this->getColumns()}";
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

        if ($this->limit != null) {
            $sql .= " LIMIT {$this->limit}";
        }

        return $sql;
    }
}

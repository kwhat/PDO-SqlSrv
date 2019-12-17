<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\SqlSrv;

use FaaPz\PDO;

class Database extends PDO\Database
{
    /**
     * @param array<int|string, string> $columns
     *
     * @return Statement\Select
     */
    public function select(array $columns = ['*']): PDO\Statement\Select
    {
        return new Statement\Select($this, $columns);
    }
}

<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\SqlSrv\Clause;

use FaaPz\PDO;

/**
 * @property int $offset
 * @property int|null $size
 */
class Offset extends PDO\Clause\Limit
{
    /**
     * @param int      $offset
     * @param int|null $size
     */
    public function __construct(int $offset, ?int $size = null)
    {
        parent::__construct(0, $offset);

        $this->size = $size;
    }

    /**
     * @return int[]
     */
    public function getValues(): array
    {
        $values = [$this->offset];
        if (isset($this->size)) {
            $values[] = $this->size;
        }

        return $values;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = 'OFFSET ?';
        if ($this->size !== null) {
            $sql .= ' ROWS FETCH NEXT ? ROWS ONLY';
        }

        return $sql;
    }
}

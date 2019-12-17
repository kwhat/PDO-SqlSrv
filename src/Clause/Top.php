<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\SqlSrv\Clause;

use FaaPz\PDO;

/**
 * @property int $size
 */
class Top extends PDO\Clause\Limit
{
    /** @var bool $percent */
    protected $percent;

    /**
     * @param int  $size
     * @param bool $percent
     */
    public function __construct(int $size, bool $percent = false)
    {
        parent::__construct($size);

        $this->percent = $percent;
    }

    /**
     * @return int[]
     */
    public function getValues(): array
    {
        return [$this->size];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = 'TOP ?';
        if ($this->percent) {
            $sql .= ' PERCENT';
        }

        return $sql;
    }
}

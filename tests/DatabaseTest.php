<?php

/**
 * @license MIT
 * @license http://opensource.org/licenses/MIT
 */

namespace FaaPz\PDO\SqlSrv\Test;

use FaaPz\PDO\SqlSrv\Database;
use FaaPz\PDO\SqlSrv\Statement;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class DatabaseTest extends TestCase
{
    /** @var Database $subject */
    private $subject;

    public function setUp(): void
    {
        parent::setUp();

        $ref = new ReflectionClass(Database::class);
        $this->subject = $ref->newInstanceWithoutConstructor();
    }

    public function testSelect()
    {
        $this->assertInstanceOf(
            Statement\Select::class,
            $this->subject->select()
        );
    }
}

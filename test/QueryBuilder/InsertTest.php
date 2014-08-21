<?php

namespace Modules\DBAL\QueryBuilder;

use Miny\Log\NullLog;
use Modules\DBAL\Driver;
use Modules\DBAL\Platform;

class InsertTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Driver
     */
    private $driver;

    public function setUp()
    {
        $platform = $this->getMockForAbstractClass('\\Modules\\DBAL\\Platform');
        $this->driver   = $this->getMockForAbstractClass(
            '\\Modules\\DBAL\\Driver',
            [$platform, new NullLog()]
        );
    }

    public function testEmptyInsert()
    {
        $insert = new Insert($this->driver);
        $insert->into('table');
        $this->assertEquals('INSERT INTO table () VALUES ()', $insert->get());
    }

    public function testInsertWithValue()
    {
        $insert = new Insert($this->driver);
        $insert
            ->into('table')
            ->values(
                [
                    'a' => '?',
                    'b' => '?'
                ]
            )
            ->set('c', '?');
        $this->assertEquals('INSERT INTO table (a, b, c) VALUES (?, ?, ?)', $insert->get());
    }

    public function testInsertWithPositionalParameters()
    {
        $insert = new Insert($this->driver);
        $insert
            ->into('table')
            ->values(
                [
                    'a' => $insert->createPositionalParameter('foo'),
                    'b' => $insert->createPositionalParameter('bar')
                ]
            );
        $this->assertEquals(
            'INSERT INTO table (a, b) VALUES (?, ?)',
            $insert->get()
        );
    }

    public function testInsertWithNamedParameters()
    {
        $insert = new Insert($this->driver);
        $insert
            ->into('table')
            ->values(
                [
                    'a' => $insert->createNamedParameter('foo'),
                    'b' => $insert->createNamedParameter('bar')
                ]
            );
        $this->assertEquals(
            'INSERT INTO table (a, b) VALUES (:parameter1, :parameter2)',
            $insert->get()
        );
    }
}

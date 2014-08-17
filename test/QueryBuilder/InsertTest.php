<?php

namespace Modules\DBAL\QueryBuilder;

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
        $this->driver = $this->getMockForAbstractClass(
            '\\Modules\\DBAL\\Driver',
            array(),
            'DriverMock',
            false
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
                array(
                    'a' => '?',
                    'b' => '?'
                )
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
                array(
                    'a' => $insert->createPositionalParameter('foo'),
                    'b' => $insert->createPositionalParameter('bar')
                )
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
                array(
                    'a' => $insert->createNamedParameter('foo'),
                    'b' => $insert->createNamedParameter('bar')
                )
            );
        $this->assertEquals(
            'INSERT INTO table (a, b) VALUES (:parameter1, :parameter2)',
            $insert->get()
        );
    }
}
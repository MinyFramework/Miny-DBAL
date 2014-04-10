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
        $platform = $this->getMockForAbstractClass('\\Modules\\DBAL\\Platform');
        $this->driver   = $this->getMockForAbstractClass('\\Modules\\DBAL\\Driver', array($platform));
    }

    public function testEmptyInsert()
    {
        $insert = new Insert($this->driver);
        $insert->into('table');
        $this->assertEquals('INSERT INTO table () VALUES ()', $insert->get());

        $insert->values(
            array(
                'a' => '?',
                'b' => '?'
            )
        );
        $insert->set('c', '?');
        $this->assertEquals('INSERT INTO table (a, b, c) VALUES (?, ?, ?)', $insert->get());
    }
}

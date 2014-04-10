<?php

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\Platform;

class InsertTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Platform
     */
    private $platform;

    public function setUp()
    {
        $this->platform = $this->getMockForAbstractClass('\\Modules\\DBAL\\Platform');
    }

    public function testEmptyInsert()
    {
        $insert = new Insert($this->platform);
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

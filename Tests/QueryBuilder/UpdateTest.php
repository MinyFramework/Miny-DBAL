<?php

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\Driver;
use Modules\DBAL\Platform;

class UpdateTest extends \PHPUnit_Framework_TestCase
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

    public function testUpdate()
    {
        $update = new Update($this->driver);
        $update->update('table');
        $update->set('a', '?');
        $update->set('b', '?');
        $update->setValues(
            array(
                'c' => '?',
                'd' => '?'
            )
        );

        $update->where('c=d');

        $this->assertEquals('UPDATE table SET a=?, b=?, c=?, d=? WHERE c=d', $update->get());
    }
}

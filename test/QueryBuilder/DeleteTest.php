<?php

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\Driver;
use Modules\DBAL\Platform;

class DeleteTest extends \PHPUnit_Framework_TestCase
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

    public function testDelete()
    {
        $delete = new Delete($this->driver);
        $delete->from('table');
        $delete->where('c=d');

        $this->assertEquals('DELETE FROM table WHERE c=d', $delete->get());
    }
}

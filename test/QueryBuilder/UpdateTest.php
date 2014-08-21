<?php

namespace Modules\DBAL\QueryBuilder;

use Miny\Log\NullLog;
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
        $platform = $this->getMockForAbstractClass('\\Modules\\DBAL\\Platform');
        $this->driver   = $this->getMockForAbstractClass(
            '\\Modules\\DBAL\\Driver',
            [$platform, new NullLog()]
        );
    }

    public function testUpdate()
    {
        $update = new Update($this->driver);
        $update->update('table')
            ->set('a', '?')
            ->set('b', '?')
            ->values(
                [
                    'c' => '?',
                    'd' => '?'
                ]
            )
            ->where('c=d');

        $this->assertEquals('UPDATE table SET a=?, b=?, c=?, d=? WHERE c=d', $update->get());
    }
}

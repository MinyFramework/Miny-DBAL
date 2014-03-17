<?php

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\Platform;

class UpdateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Platform
     */
    private $platform;

    public function setUp()
    {
        $this->platform = $this->getMockBuilder(
            '\\Modules\\DBAL\\Platform'
        )->getMock();
    }

    public function testUpdate()
    {
        $update = new Update($this->platform);
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

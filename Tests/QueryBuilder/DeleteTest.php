<?php

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\Platform;

class DeleteTest extends \PHPUnit_Framework_TestCase
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
        $delete = new Delete($this->platform);
        $delete->from('table');
        $delete->where('c=d');

        $this->assertEquals('DELETE FROM table WHERE c=d', $delete->get());
    }
}

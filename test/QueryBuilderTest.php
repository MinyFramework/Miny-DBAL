<?php

namespace Modules\DBAL;

class QueryBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var QueryBuilder
     */
    private $builder;

    public function setUp()
    {
        $platform = $this->getMockBuilder('\\Modules\\DBAL\\Platform')
            ->getMock();

        $driver = $this->getMockBuilder('\\Modules\\DBAL\\Driver')
            ->disableOriginalConstructor()
            ->getMock();

        $driver->expects($this->any())
            ->method('getPlatform')
            ->will($this->returnValue($platform));

        $this->builder = new QueryBuilder($driver);
    }

    public function testThatTheCorrectTypesAreReturned()
    {
        $select = $this->builder->select('*');
        $this->assertInstanceOf(
            '\\Modules\\DBAL\\QueryBuilder\\Select',
            $select
        );

        $insert = $this->builder->insert('table');
        $this->assertInstanceOf(
            '\\Modules\\DBAL\\QueryBuilder\\Insert',
            $insert
        );

        $update = $this->builder->update('table');
        $this->assertInstanceOf(
            '\\Modules\\DBAL\\QueryBuilder\\Update',
            $update
        );

        $delete = $this->builder->delete('table');
        $this->assertInstanceOf(
            '\\Modules\\DBAL\\QueryBuilder\\Delete',
            $delete
        );

        $expr = $this->builder->expression();
        $this->assertInstanceOf(
            '\\Modules\\DBAL\\QueryBuilder\\Expression',
            $expr
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testParameterTypesCannotBeMixed()
    {
        $this->builder->select('*');
        $this->assertEquals('?', $this->builder->createPositionalParameter(0));
        $this->assertEquals(':parameter3', $this->builder->createNamedParameter(0));
    }

    public function testParameterMethods()
    {
        $this->builder->select('*');
        $this->assertEquals('?', $this->builder->createPositionalParameter(0));
        $this->assertEquals(['?', '?'], $this->builder->createPositionalParameter([0, 1]));
        $this->builder->select('*');
        $this->assertEquals(':parameter0', $this->builder->createNamedParameter(0));
        $this->assertEquals([':parameter1', ':parameter2'], $this->builder->createNamedParameter([0, 1]));
    }
}

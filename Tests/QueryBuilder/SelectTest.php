<?php

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\Platform;

class SelectTest extends \PHPUnit_Framework_TestCase
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

    public function testSelect()
    {
        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't');
        $this->assertEquals('SELECT * FROM table t', $select->get());

        $select = new Select($this->platform);
        $select->select('a', 'b')
            ->from('table', 't')
            ->where('a = b')
            ->groupBy('gr')
            ->orderBy('ord')
            ->lockForUpdate();

        $this->assertEquals(
            'SELECT a, b FROM table t WHERE a = b GROUP BY gr ORDER BY ord ASC FOR UPDATE',
            $select->get()
        );

        $select = new Select($this->platform);
        $select->select('a')
            ->addSelect('b', 'c')
            ->addSelect('d')
            ->from('table', 't');
        $this->assertEquals('SELECT a, b, c, d FROM table t', $select->get());
    }

    public function testSelectFromSelect()
    {
        $innerSelect = new Select($this->platform);
        $innerSelect->select('*');
        $innerSelect->from('table');

        $select = new Select($this->platform);
        $select->select('a', 'b')
            ->from($innerSelect, 't');

        $this->assertEquals('SELECT a, b FROM (SELECT * FROM table) t', $select->get());
    }

    public function testJoinedSelect()
    {
        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't')
            ->join('t', 'other', 'o', 'o.a = t.a');

        $this->assertEquals(
            'SELECT * FROM table t INNER JOIN other o ON o.a = t.a',
            $select->get()
        );
    }

    public function testSelectWithMultipleFromClauses()
    {
        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't')
            ->from('other', 'o');

        $this->assertEquals('SELECT * FROM table t, other o', $select->get());
    }

    public function testSelectWithOrderByClause()
    {
        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't')
            ->orderBy('field', 'DESC')
            ->orderBy('field2', 'DESC');

        $this->assertEquals('SELECT * FROM table t ORDER BY field2 DESC', $select->get());

        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't')
            ->orderBy('field', 'DESC')
            ->addOrderBy('field2');

        $this->assertEquals(
            'SELECT * FROM table t ORDER BY field DESC, field2 ASC',
            $select->get()
        );
    }

    public function testSelectWithGroupByClause()
    {
        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't')
            ->groupBy('field')
            ->groupBy('field2');

        $this->assertEquals('SELECT * FROM table t GROUP BY field2', $select->get());

        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't')
            ->groupBy('field', 'field2');

        $this->assertEquals(
            'SELECT * FROM table t GROUP BY field, field2',
            $select->get()
        );

        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't')
            ->groupBy('field')
            ->addGroupBy('field2');

        $this->assertEquals(
            'SELECT * FROM table t GROUP BY field, field2',
            $select->get()
        );
    }

    public function testSelectWithHavingClause()
    {
        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't')
            ->groupBy('id')
            ->having('name = ?');

        $this->assertEquals('SELECT * FROM table t GROUP BY id HAVING name = ?', $select->get());

    }

    public function testThatUnknownAliasesShouldNotBeJoined()
    {
        $select = new Select($this->platform);
        $select->select('*')
            ->from('table', 't')
            ->join('foo', 'other', 'o', 'o.a = t.a');

        $this->assertEquals('SELECT * FROM table t', $select->get());
    }
}

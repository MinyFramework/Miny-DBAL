<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\AbstractQueryBuilder;
use Modules\DBAL\QueryBuilder\Traits\GroupByTrait;
use Modules\DBAL\QueryBuilder\Traits\HavingTrait;
use Modules\DBAL\QueryBuilder\Traits\JoinTrait;
use Modules\DBAL\QueryBuilder\Traits\LimitTrait;
use Modules\DBAL\QueryBuilder\Traits\OrderByTrait;
use Modules\DBAL\QueryBuilder\Traits\WhereTrait;
use UnexpectedValueException;

class Select extends AbstractQueryBuilder
{
    use WhereTrait;
    use HavingTrait;
    use JoinTrait;
    use LimitTrait;
    use OrderByTrait;
    use GroupByTrait;

    private $columns = [];
    private $lock = false;
    private $from;

    public function select($column)
    {
        $this->columns = is_array($column) ? $column : func_get_args();

        return $this;
    }

    public function addSelect($column)
    {
        $columns       = is_array($column) ? $column : func_get_args();
        $this->columns = array_merge($this->columns, $columns);

        return $this;
    }

    public function from($from, $alias = null)
    {
        if ($from instanceof Select) {
            $from = '(' . $from->get() . ')';
        }

        $this->from[] = [$from, $alias];

        return $this;
    }

    public function lockForUpdate($lock = true)
    {
        $this->lock = $lock;

        return $this;
    }

    public function get()
    {
        return $this->getSelectPart() .
        $this->getFromPart() .
        $this->getWhere() .
        $this->getGroupByPart() .
        $this->getHaving() .
        $this->getOrderByPart() .
        $this->getLimitingPart() .
        $this->getLockPart();
    }

    private function getFromPart()
    {
        if (empty($this->from)) {
            throw new UnexpectedValueException('Select query must have a FROM clause.');
        }
        $first = true;
        $from  = ' FROM ';
        foreach ($this->from as $part) {
            if ($first) {
                $first = false;
            } else {
                $from .= ', ';
            }

            list($table, $alias) = $part;
            if ($alias && $alias !== $table) {
                $table .= ' ' . $alias;
            } else {
                $alias = $table;
            }

            $table .= $this->getJoinPart($alias);
            $from .= $table;
        }

        return $from;
    }


    private function getLockPart()
    {
        if (!$this->lock) {
            return '';
        }

        return ' FOR UPDATE';
    }

    private function getSelectPart()
    {
        return 'SELECT ' . implode(', ', $this->columns);
    }
}

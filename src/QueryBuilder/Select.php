<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder;

use UnexpectedValueException;

class Select extends AbstractSelect
{
    private $columns = array();
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

        $this->from[] = array($from, $alias);

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
        $this->getWherePart() .
        $this->getGroupByPart() .
        $this->getHavingPart() .
        $this->getOrderByPart() .
        $this->getLimitingPart() .
        $this->getLockPart();
    }

    private function getFromPart()
    {
        if (empty($this->from)) {
            throw new UnexpectedValueException('Select query must have a FROM clause.');
        }
        $fromParts = array();
        foreach ($this->from as $from) {
            $query = $from[0];
            if ($from[1] && $from[1] !== $from[0]) {
                $query .= ' ' . $from[1];
            } else {
                $from[1] = $from[0];
            }

            $query .= $this->getJoinPart($from[1]);

            $fromParts[] = $query;
        }

        return ' FROM ' . implode(', ', $fromParts);
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

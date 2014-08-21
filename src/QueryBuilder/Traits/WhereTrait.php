<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder\Traits;

trait WhereTrait
{
    private $where;

    public function where($expression)
    {
        $this->where = $expression;

        return $this;
    }

    public function andWhere($expression)
    {
        $this->where = '(' . $this->where . ') AND ' . $expression;

        return $this;
    }

    public function orWhere($expression)
    {
        $this->where = '(' . $this->where . ') OR ' . $expression;

        return $this;
    }

    public function getWhere()
    {
        if (!$this->where) {
            return '';
        }

        return ' WHERE ' . $this->where;
    }
}

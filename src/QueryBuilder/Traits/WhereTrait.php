<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder\Traits;

use Modules\DBAL\QueryBuilder\Expression;

trait WhereTrait
{
    private $where;

    public function where($expression)
    {
        $this->where = Expression::toString($expression);

        return $this;
    }

    public function andWhere($expression)
    {
        $expression  = Expression::toString($expression);
        $this->where = '(' . $this->where . ') AND ' . $expression;

        return $this;
    }

    public function orWhere($expression)
    {
        $expression  = Expression::toString($expression);
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

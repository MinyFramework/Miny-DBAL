<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder\Traits;

use Modules\DBAL\QueryBuilder\Expression;

trait HavingTrait
{
    private $having;

    public function having($expression)
    {
        $this->having = Expression::toString($expression);

        return $this;
    }

    public function andHaving($expression)
    {
        $expression  = Expression::toString($expression);
        $this->having = '(' . $this->having . ') AND ' . $expression;

        return $this;
    }

    public function orHaving($expression)
    {
        $expression  = Expression::toString($expression);
        $this->having = '(' . $this->having . ') OR ' . $expression;

        return $this;
    }

    public function getHaving()
    {
        if (!$this->having) {
            return '';
        }

        return ' HAVING ' . $this->having;
    }
}

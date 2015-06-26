<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder\Traits;

trait HavingTrait
{
    private $having;

    public function setHaving($expression)
    {
        $this->having = $expression;

        return $this;
    }

    public function having($expression)
    {
        if ($this->having === null) {
            $this->having = $expression;
        } else {
            $this->andHaving($expression);
        }

        return $this;
    }

    public function andHaving($expression)
    {
        $this->having = '(' . $this->having . ') AND ' . $expression;

        return $this;
    }

    public function orHaving($expression)
    {
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

<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder\Traits;

use Modules\DBAL\QueryBuilder\Expression;

trait JoinTrait
{
    private $joins = [];

    private function addJoin($type, $left, $table, $alias, $condition = null)
    {
        $condition = Expression::toString($condition);
        if (!isset($this->joins[$left])) {
            $this->joins[$left] = [];
        }
        $this->joins[$left][] = [
            $type,
            $table,
            $alias,
            $condition
        ];

        return $this;
    }

    public function join($left, $table, $alias = null, $condition = null)
    {
        return $this->addJoin(' INNER JOIN ', $left, $table, $alias, $condition);
    }

    public function leftJoin($left, $table, $alias = null, $condition = null)
    {
        return $this->addJoin(' LEFT JOIN ', $left, $table, $alias, $condition);
    }

    public function rightJoin($left, $table, $alias = null, $condition = null)
    {
        return $this->addJoin(' RIGHT JOIN ', $left, $table, $alias, $condition);
    }

    public function fullJoin($left, $table, $alias = null, $condition = null)
    {
        return $this->addJoin(' FULL JOIN ', $left, $table, $alias, $condition);
    }

    public function getJoinPart($alias)
    {
        if (!isset($this->joins[$alias])) {
            return '';
        }
        $query = '';
        foreach ($this->joins[$alias] as $join) {
            list($type, $table, $alias, $condition) = $join;

            $query .= $type . $table;

            if ($alias && $alias !== $table) {
                $query .= ' ' . $alias;
            } else {
                $alias = $table;
            }

            if ($condition) {
                $query .= ' ON ' . $join[3];
            }

            $query .= $this->getJoinPart($alias);
        }

        return $query;
    }
}

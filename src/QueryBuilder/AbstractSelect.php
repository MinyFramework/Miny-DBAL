<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\AbstractQueryBuilder;

abstract class AbstractSelect extends AbstractQueryBuilder
{
    const JOIN_LEFT  = ' LEFT JOIN ';
    const JOIN_RIGHT = ' RIGHT JOIN ';
    const JOIN_INNER = ' INNER JOIN ';
    const JOIN_FULL  = ' FULL JOIN ';

    private $joins = array();
    private $where;
    private $having;
    private $limit;
    private $offset;
    private $groupByFields = array();
    private $orderByFields = array();

    abstract public function from($table);

    public function where($expression)
    {
        $expression  = $this->getExpressionAsString($expression);
        $this->where = $expression;

        return $this;
    }

    public function andWhere($expression)
    {
        $expression  = $this->getExpressionAsString($expression);
        $this->where = '(' . $this->where . ') AND ' . $expression;

        return $this;
    }

    public function orWhere($expression)
    {
        $expression  = $this->getExpressionAsString($expression);
        $this->where = '(' . $this->where . ') OR ' . $expression;

        return $this;
    }

    public function having($expression)
    {
        $expression   = $this->getExpressionAsString($expression);
        $this->having = $expression;

        return $this;
    }

    public function andHaving($expression)
    {
        $expression   = $this->getExpressionAsString($expression);
        $this->having = '(' . $this->having . ') AND ' . $expression;

        return $this;
    }

    public function orHaving($expression)
    {
        $expression   = $this->getExpressionAsString($expression);
        $this->having = '(' . $this->having . ') OR ' . $expression;

        return $this;
    }

    private function addJoin($type, $left, $table, $alias, $condition = null)
    {
        $condition = $this->getExpressionAsString($condition);
        if (!isset($this->joins[$left])) {
            $this->joins[$left] = array();
        }
        $this->joins[$left][] = array(
            $type,
            $table,
            $alias,
            $condition
        );

        return $this;
    }

    public function join($left, $table, $alias = null, $condition = null)
    {
        return $this->addJoin(self::JOIN_INNER, $left, $table, $alias, $condition);
    }

    public function leftJoin($left, $table, $alias = null, $condition = null)
    {
        return $this->addJoin(self::JOIN_LEFT, $left, $table, $alias, $condition);
    }

    public function rightJoin($left, $table, $alias = null, $condition = null)
    {
        return $this->addJoin(self::JOIN_RIGHT, $left, $table, $alias, $condition);
    }

    public function fullJoin($left, $table, $alias = null, $condition = null)
    {
        return $this->addJoin(self::JOIN_FULL, $left, $table, $alias, $condition);
    }

    public function groupBy($field)
    {
        $this->groupByFields = is_array($field) ? $field : func_get_args();

        return $this;
    }

    public function addGroupBy($field)
    {
        $fields = is_array($field) ? $field : func_get_args();

        $this->groupByFields = array_merge($this->groupByFields, $fields);

        return $this;
    }

    public function orderBy($field, $order = 'ASC')
    {
        if (strtoupper($order) !== 'ASC') {
            $order = 'DESC';
        }
        $this->orderByFields = array($field => $field . ' ' . $order);

        return $this;
    }

    public function addOrderBy($field, $order = 'ASC')
    {
        if ($order !== 'ASC') {
            $order = 'DESC';
        }
        $this->orderByFields[$field] = $field . ' ' . $order;

        return $this;
    }

    public function setMaxResults($limit)
    {
        $this->limit = (int)$limit;

        return $this;
    }

    public function setFirstResult($offset)
    {
        $this->offset = (int)$offset;

        return $this;
    }

    protected function getJoinPart($alias)
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

    protected function getWherePart()
    {
        if (!$this->where) {
            return '';
        }

        return ' WHERE ' . $this->where;
    }

    protected function getGroupByPart()
    {
        if (empty($this->groupByFields)) {
            return '';
        }

        return ' GROUP BY ' . join(', ', $this->groupByFields);
    }

    protected function getHavingPart()
    {
        if (!$this->having) {
            return '';
        }

        return ' HAVING ' . $this->having;
    }

    protected function getOrderByPart()
    {
        if (empty($this->orderByFields)) {
            return '';
        }

        return ' ORDER BY ' . join(', ', $this->orderByFields);
    }

    protected function getLimitingPart()
    {
        return $this->getPlatform()->getLimitAndOffset($this->limit, $this->offset);
    }

    /**
     * @param $expression
     *
     * @return string
     */
    protected function getExpressionAsString($expression)
    {
        if ($expression instanceof Expression) {
            return $expression->get();
        }

        return $expression;
    }
}

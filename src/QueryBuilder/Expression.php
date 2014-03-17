<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder;

use InvalidArgumentException;

class Expression
{
    const OPERATOR_EQ  = '=';
    const OPERATOR_NEQ = '<>';
    const OPERATOR_LT  = '<';
    const OPERATOR_LTE = '<=';
    const OPERATOR_GT  = '>';
    const OPERATOR_GTE = '>=';
    const OPERATOR_IN  = ' IN';
    const OPERATOR_NOT_IN = ' NOT IN';
    const OPERATOR_LIKE = ' LIKE ';
    const OPERATOR_NOT_LIKE = ' NOT LIKE ';

    private $parts = array();

    public function compare($a, $operator, $b)
    {
        $this->parts[] = $a . $operator . $b;

        return $this;
    }

    public function andX($expr)
    {
        if ($expr !== $this) {
            $this->parts[] = $expr;
        }
        $num = func_num_args();
        $parts = array();
        while ($num-- >= 0) {
            array_unshift($parts, array_pop($this->parts));
        }
        $this->parts[] = '(' . implode(' AND ', $parts) . ')';

        return $this;
    }

    public function orX($expr)
    {
        if ($expr !== $this) {
            $this->parts[] = $expr;
        }
        $num = func_num_args();
        $parts = array();
        while($num-- >= 0) {
            array_unshift($parts, array_pop($this->parts));
        }
        $this->parts[] = '(' . implode(' OR ', $parts) . ')';

        return $this;
    }

    public function lt($a, $b)
    {
        return $this->compare($a, self::OPERATOR_LT, $b);
    }

    public function lte($a, $b)
    {
        return $this->compare($a, self::OPERATOR_LTE, $b);
    }

    public function gt($a, $b)
    {
        return $this->compare($a, self::OPERATOR_GT, $b);
    }

    public function gte($a, $b)
    {
        return $this->compare($a, self::OPERATOR_GTE, $b);
    }

    public function eq($a, $b)
    {
        return $this->compare($a, self::OPERATOR_EQ, $b);
    }

    public function neq($a, $b)
    {
        return $this->compare($a, self::OPERATOR_NEQ, $b);
    }

    public function like($a, $b)
    {
        return $this->compare($a, self::OPERATOR_LIKE, $b);
    }

    public function notLike($a, $b)
    {
        return $this->compare($a, self::OPERATOR_NOT_LIKE, $b);
    }

    public function isNull($a)
    {
        $this->parts[] = $a . ' IS NULL';

        return $this;
    }

    public function isNotNull($a)
    {
        $this->parts[] = $a . ' IS NOT NULL';

        return $this;
    }

    public function between($a, $b, $c)
    {
        $this->parts[] = $a . ' BETWEEN ' . $b . ' AND ' . $c;

        return $this;
    }

    public function notBetween($a, $b, $c)
    {
        $this->parts[] = $a . ' NOT BETWEEN ' . $b . ' AND ' . $c;

        return $this;
    }

    public function in($a, $in)
    {
        if (is_array($in)) {
            $in = implode(',', $in);
        } elseif ($in instanceof Select) {
            $in = $in->get();
        } elseif (!is_string($in)) {
            $message = 'In expects an array, a Select object or a string.';
            throw new InvalidArgumentException($message);
        }

        return $this->compare($a, self::OPERATOR_IN, '(' . $in . ')');
    }

    public function notIn($a, $in)
    {
        if (is_array($in)) {
            $in = implode(',', $in);
        } elseif ($in instanceof Select) {
            $in = $in->get();
        } elseif (!is_string($in)) {
            $message = 'NotIn expects an array, a Select object or a string.';
            throw new InvalidArgumentException($message);
        }

        return $this->compare($a, self::OPERATOR_NOT_IN, '(' . $in . ')');
    }

    public function get()
    {
        return $this->parts[0];
    }
}

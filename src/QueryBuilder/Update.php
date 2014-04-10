<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\AbstractQueryBuilder;
use UnexpectedValueException;

class Update extends AbstractQueryBuilder
{
    private $table;
    private $values = array();
    private $where;

    public function update($table)
    {
        $this->table = $table;
    }

    public function values(array $values)
    {
        foreach ($values as $name => $value) {
            $this->set($name, $value);
        }
    }

    public function set($name, $value)
    {
        $this->values[] = $name . '=' . $value;
    }

    public function setValues($array)
    {
        foreach ($array as $field => $value) {
            $this->set($field, $value);
        }
    }

    public function where($expression)
    {
        if ($expression instanceof Expression) {
            $expression = $expression->get();
        }
        $this->where = $expression;

        return $this;
    }

    public function get()
    {
        return $this->getUpdatePart() .
        $this->getSetPart() .
        $this->getWherePart();
    }

    private function getWherePart()
    {
        if (!$this->where) {
            return '';
        }

        return ' WHERE ' . $this->where;
    }

    private function getUpdatePart()
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('Update query must have a table to update.');
        }

        return 'UPDATE ' . $this->table;
    }

    private function getSetPart()
    {
        return ' SET ' . implode(', ', $this->values);
    }
}

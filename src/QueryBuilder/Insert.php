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

class Insert extends AbstractQueryBuilder
{
    private $table;
    private $values = array();

    public function into($table)
    {
        $this->table = $table;
    }

    public function values(array $values)
    {
        $this->values = array_merge($this->values, $values);
    }

    public function set($name, $value)
    {
        $this->values[$name] = $value;
    }

    public function get()
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('Insert query must have an INTO clause.');
        }
        $keys   = implode(', ', array_keys($this->values));
        $values = implode(', ', $this->values);

        return 'INSERT INTO ' . $this->table . ' (' . $keys . ') VALUES (' . $values . ')';
    }
}

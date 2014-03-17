<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL;

use Modules\DBAL\QueryBuilder\Delete;
use Modules\DBAL\QueryBuilder\Expression;
use Modules\DBAL\QueryBuilder\Insert;
use Modules\DBAL\QueryBuilder\Select;
use Modules\DBAL\QueryBuilder\Update;

class QueryBuilder
{
    /**
     * @var Driver
     */
    private $driver;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    public function select($column)
    {
        $builder = new Select($this->driver->getPlatform());

        $columns = is_array($column) ? $column : func_get_args();
        $builder->select($columns);

        return $builder;
    }

    public function insert($table, array $values = null)
    {
        $builder = new Insert($this->driver->getPlatform());

        $builder->into($table);
        if ($values) {
            $builder->values($values);
        }

        return $builder;
    }

    public function update($table)
    {
        $builder = new Update($this->driver->getPlatform());
        $builder->update($table);

        return $builder;
    }

    public function delete($from)
    {
        $builder = new Delete($this->driver->getPlatform());
        $builder->from($from);

        return $builder;
    }

    public function expression()
    {
        return new Expression();
    }
}

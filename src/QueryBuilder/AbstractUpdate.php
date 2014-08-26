<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\AbstractQueryBuilder;

abstract class AbstractUpdate extends AbstractQueryBuilder
{
    protected $values = [];

    public function values(array $values)
    {
        $this->values = array_merge($this->values, $values);

        return $this;
    }

    public function set($name, $value)
    {
        $this->values[$name] = $value;

        return $this;
    }
}

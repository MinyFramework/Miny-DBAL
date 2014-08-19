<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder\Traits;

use Modules\DBAL\Platform;

trait GroupByTrait
{
    private $groupByFields = [];

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

    protected function getGroupByPart()
    {
        if (empty($this->groupByFields)) {
            return '';
        }

        return ' GROUP BY ' . join(', ', $this->groupByFields);
    }
}

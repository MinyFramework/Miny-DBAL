<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\AbstractQueryBuilder;
use Modules\DBAL\QueryBuilder\Traits\WhereTrait;
use UnexpectedValueException;

class Update extends AbstractUpdate
{
    use WhereTrait;

    private $table;

    public function update($table)
    {
        $this->table = $table;

        return $this;
    }

    public function get()
    {
        return $this->getUpdatePart() .
        $this->getSetPart() .
        $this->getWhere();
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
        $set = ' SET ';

        $first = true;
        foreach ($this->values as $key => $value) {
            if ($first) {
                $first = false;
            } else {
                $set .= ', ';
            }
            $set .= $key . '=' . $value;
        }

        return $set;
    }
}

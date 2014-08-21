<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\AbstractQueryBuilder;
use Modules\DBAL\QueryBuilder\Traits\LimitTrait;
use Modules\DBAL\QueryBuilder\Traits\OrderByTrait;
use Modules\DBAL\QueryBuilder\Traits\WhereTrait;
use UnexpectedValueException;

class Delete extends AbstractQueryBuilder
{
    use WhereTrait;
    use LimitTrait;
    use OrderByTrait;

    private $table;

    public function from($table)
    {
        $this->table = $table;

        return $this;
    }

    public function get()
    {
        return $this->getFromPart() .
        $this->getWhere() .
        $this->getOrderByPart() .
        $this->getLimitingPart();
    }

    private function getFromPart()
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('Delete query must have a FROM clause.');
        }

        return 'DELETE FROM ' . $this->table;
    }
}

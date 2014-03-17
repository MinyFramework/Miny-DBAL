<?php

namespace Modules\DBAL\QueryBuilder;

use Modules\DBAL\AbstractQueryBuilder;
use UnexpectedValueException;

class Delete extends AbstractQueryBuilder
{
    private $table;
    private $where;

    public function from($table)
    {
        $this->table = $table;
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
        return $this->getFromPart() . $this->getWherePart();
    }

    private function getWherePart()
    {
        if (!$this->where) {
            return '';
        }

        return ' WHERE ' . $this->where;
    }

    private function getFromPart()
    {
        if (!isset($this->table)) {
            throw new UnexpectedValueException('Delete query must have a FROM clause.');
        }

        return 'DELETE FROM ' . $this->table;
    }
}

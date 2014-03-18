<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL;

use InvalidArgumentException;
use Modules\DBAL\Driver\Statement;

abstract class Driver
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var Platform
     */
    private $platform;

    public function __construct(Platform $platform)
    {
        $this->platform = $platform;
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function getQueryBuilder()
    {
        if (!isset($this->queryBuilder)) {
            $this->queryBuilder = new QueryBuilder($this);
        }

        return $this->queryBuilder;
    }

    public function inTransaction($function)
    {
        if (!is_callable($function)) {
            throw new InvalidArgumentException('$function must be callable.');
        }
        $this->beginTransaction();
        try {
            $function($this);
            $this->commit();
        } catch (\PDOException $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function fetch($query, array $params = null)
    {
        return $this->query($query, $params)->fetch();
    }

    public function fetchAll($query, array $params = null)
    {
        return $this->query($query, $params)->fetchAll();
    }

    public function fetchColumn($query, array $params = null, $columnNumber = 0)
    {
        return $this->query($query, $params)->fetchColumn($columnNumber);
    }

    abstract public function setAttribute($name, $value);

    abstract public function getAttribute($name);

    /**
     * @param       $query
     * @param array $params
     *
     * @return Statement
     */
    abstract public function query($query, array $params = null);

    /**
     * @param $query
     *
     * @return Statement
     */
    abstract public function prepare($query);

    abstract public function lastInsertId($name = null);

    abstract public function beginTransaction();

    abstract public function commit();

    abstract public function rollback();

    /**
     * Quotes an identifier (e.g. table, column) to be safe to use in queries.
     * @param string $identifier
     *
     * @return string The quoted identifier.
     */
    public function quoteIdentifier($identifier)
    {
        return $this->platform->quoteIdentifier($identifier);
    }

    abstract public function quoteLiteral($literal, $type = null);
}

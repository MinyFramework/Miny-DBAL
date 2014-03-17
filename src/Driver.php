<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL;

use InvalidArgumentException;

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

    abstract public function setAttribute($name, $value);

    abstract public function getAttribute($name);

    abstract public function query($query);

    abstract public function prepare($query);

    abstract public function beginTransaction();

    abstract public function commit();

    abstract public function rollback();

    abstract public function quoteLiteral($literal, $type = null);
}

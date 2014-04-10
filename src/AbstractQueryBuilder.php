<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL;

abstract class AbstractQueryBuilder
{
    /**
     * @var Driver
     */
    private $driver;

    /**
     * @param Driver $driver
     */
    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return Platform
     */
    public function getPlatform()
    {
        return $this->driver->getPlatform();
    }

    public function __toString()
    {
        return $this->get();
    }

    abstract public function get();
}

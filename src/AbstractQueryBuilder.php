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
     * @var array
     */
    private $parameters = array();

    private $parameterCounter = 0;

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

    public function setParameter($num, $value)
    {
        $this->parameters[$num] = $value;
    }

    public function query(array $parameters = array())
    {
        return $this->driver->query($this->get(), $parameters + $this->parameters);
    }

    public function createPositionalParameter($value)
    {
        $this->setParameter(++$this->parameterCounter, $value);

        return '?';
    }

    public function createNamedParameter($value)
    {
        $name = ':parameter' . ++$this->parameterCounter;
        $this->setParameter($name, $value);

        return $name;
    }

    abstract public function get();
}

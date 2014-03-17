<?php

namespace Modules\DBAL;

abstract class AbstractQueryBuilder
{
    /**
     * @var Platform
     */
    private $platform;

    /**
     * @param Platform $platform
     */
    public function __construct(Platform $platform)
    {
        $this->platform = $platform;
    }

    /**
     * @return Platform
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    public function __toString()
    {
        return $this->get();
    }

    abstract public function get();
}

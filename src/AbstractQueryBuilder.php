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

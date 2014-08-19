<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\QueryBuilder\Traits;

use Modules\DBAL\Platform;

trait LimitTrait
{
    private $limit;
    private $offset;

    public function setMaxResults($limit)
    {
        $this->limit = (int)$limit;

        return $this;
    }

    public function setFirstResult($offset)
    {
        $this->offset = (int)$offset;

        return $this;
    }

    /**
     * @return Platform
     */
    abstract public function getPlatform();

    public function getLimitingPart()
    {
        return $this->getPlatform()->getLimitAndOffset($this->limit, $this->offset);
    }
}

<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL;

abstract class Platform
{
    abstract public function getTableListingQuery();

    abstract public function getTableDetailingQuery($table);

    abstract public function getLimitAndOffset($limit, $offset);

    /**
     * Quotes an identifier (e.g. table, column) to be safe to use in queries.
     *
     * @param string $identifier
     *
     * @return string The quoted identifier.
     */
    abstract public function quoteIdentifier($identifier);
}

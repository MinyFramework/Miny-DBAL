<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\Platform;

use Modules\DBAL\Platform;

class MySQL extends Platform
{
    const MAX_LIMIT = '18446744073709551615';

    /**
     * @inheritdoc
     */
    public function quoteIdentifier($identifier)
    {
        return '`' . $identifier . '`';
    }

    public function getTableListingQuery()
    {
        return 'SHOW TABLES';
    }

    public function getTableDetailingQuery($table)
    {
        return 'DESCRIBE ' . $this->quoteIdentifier($table);
    }

    public function getLimitAndOffset($limit, $offset)
    {
        if (isset($offset)) {
            return ' LIMIT ' . ($limit ? : self::MAX_LIMIT) . ' OFFSET ' . $offset;
        }
        if (isset($limit)) {
            return ' LIMIT ' . $limit;
        }

        return '';
    }
}

<?php

namespace Modules\DBAL\Platform;

use Modules\DBAL\Platform;

class MySQL extends Platform
{
    const MAX_LIMIT = '18446744073709551615';

    public function getLimitAndOffset($limit, $offset)
    {
        if (isset($limit)) {
            $limitPart = ' LIMIT ' . $limit;
            if (isset($offset)) {
                return $limitPart . ' OFFSET ' . $offset;
            }

            return $limitPart;
        }
        if (isset($offset)) {
            return ' LIMIT ' . self::MAX_LIMIT . ' OFFSET ' . $offset;
        }

        return '';
    }
}

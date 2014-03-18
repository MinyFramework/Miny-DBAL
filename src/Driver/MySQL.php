<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\Driver;

use Modules\DBAL\Driver;
use Modules\DBAL\Platform\MySQL as MySQLPlatform;
use Modules\DBAL\Platform;

class MySQL extends PDODriver
{
    public function __construct(array $params, $user, $password, array $options = array())
    {
        parent::__construct(new MySQLPlatform());

        $this->connect($this->constructDsn($params), $user, $password, $options);
    }

    private function constructDsn(array $params)
    {
        if (isset($params['dsn'])) {
            return $params['dsn'];
        }
        $dsn = 'mysql:';

        $parts = array('host', 'port', 'dbname', 'unix_socket', 'charset');
        foreach ($parts as $part) {
            if (isset($params[$part])) {
                $dsn .= $part . '=' . $params[$part] . ';';
            }
        }

        return $dsn;
    }
}

<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\Driver;

use Miny\Log\AbstractLog;
use Modules\DBAL\Driver;
use Modules\DBAL\Platform;
use Modules\DBAL\Platform\MySQL as MySQLPlatform;

class MySQL extends PDODriver
{
    public function __construct(
        AbstractLog $log,
        array $params,
        $user,
        $password,
        array $options = []
    ) {
        parent::__construct(new MySQLPlatform(), $log);

        $this->connect($this->constructDsn($params), $user, $password, $options);
    }

    private function constructDsn(array $params)
    {
        if (isset($params['dsn'])) {
            return $params['dsn'];
        }
        $dsn = 'mysql:';

        $parts = ['host', 'port', 'dbname', 'unix_socket', 'charset'];
        foreach ($parts as $part) {
            if (isset($params[$part])) {
                $dsn .= $part . '=' . $params[$part] . ';';
            }
        }

        return $dsn;
    }
}

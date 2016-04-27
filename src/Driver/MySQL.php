<?php

namespace Modules\DBAL\Driver;

use DBTiny\Driver;
use DBTiny\Platform;
use DBTiny\Driver\MySQL as MySQLDriver;
use Miny\Log\AbstractLog;
use Miny\Log\Log;

class MySQL extends MySQLDriver
{
    /**
     * @var AbstractLog
     */
    private $log;

    public function __construct(AbstractLog $log, $params, $user, $password, array $options = [])
    {
        parent::__construct($params, $user, $password, $options);

        $this->log = $log;
    }

    public function query($query, array $params = null)
    {
        $this->log->write(Log::DEBUG, 'DBAL', 'Executing query: ' . $query);
        return parent::query($query, $params);
    }
}

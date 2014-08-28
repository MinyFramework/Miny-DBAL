<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL\Driver;

use Miny\Log\Log;
use Modules\DBAL\Driver;
use PDO;

abstract class PDODriver extends Driver
{
    const LOG_TAG = 'PDODriver';

    /**
     * @var PDO
     */
    private $pdo;
    private $transactionCounter = 0;

    protected function connect($dsn, $username, $password, array $options = [])
    {
        $this->pdo = new PDO($dsn, $username, $password, $options);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(
            PDO::ATTR_STATEMENT_CLASS,
            [__NAMESPACE__ . '\\PDOStatement', []]
        );
    }

    public function getServerVersion()
    {
        return $this->getAttribute(PDO::ATTR_SERVER_VERSION);
    }

    public function execute($query)
    {
        return $this->pdo->exec($query);
    }

    public function query($query, array $params = null)
    {
        $this->log->write(Log::DEBUG, self::LOG_TAG, 'Executing SQL Query: %s', $query);
        if (empty($params)) {
            return $this->pdo->query($query);
        }
        $statement = $this->pdo->prepare($query);
        $statement->execute($params);

        $this->logParameters($params);

        return $statement;
    }

    private function logParameters($params)
    {
        $params_str = '';
        $first      = true;
        foreach ($params as $key => $value) {
            if ($first) {
                $first = false;
            } else {
                $params_str .= ', ';
            }
            $params_str .= "{$key}: \"{$value}\"";
        }

        $this->log->write(
            Log::DEBUG,
            self::LOG_TAG,
            'Query parameters: %s',
            $params_str
        );
    }

    public function prepare($query, array $options = [])
    {
        $this->log->write(Log::DEBUG, self::LOG_TAG, 'Preparing SQL Query: %s', $query);

        return $this->pdo->prepare($query, $options);
    }

    public function beginTransaction()
    {
        if (!$this->transactionCounter++) {
            return $this->pdo->beginTransaction();
        }

        return $this->transactionCounter >= 0;
    }

    public function commit()
    {
        if (--$this->transactionCounter === 0) {
            return $this->pdo->commit();
        }

        return $this->transactionCounter >= 0;
    }

    public function rollback()
    {
        if ($this->transactionCounter >= 0) {
            $this->transactionCounter = 0;

            return $this->pdo->rollback();
        }
        $this->transactionCounter = 0;

        return false;
    }

    public function quoteLiteral($literal, $type = null)
    {
        return $this->pdo->quote($literal, $type ? : PDO::PARAM_STR);
    }

    public function setAttribute($name, $value)
    {
        return $this->pdo->setAttribute($name, $value);
    }

    public function getAttribute($name)
    {
        return $this->pdo->getAttribute($name);
    }

    public function lastInsertId($name = null)
    {
        return $this->pdo->lastInsertId($name);
    }
}

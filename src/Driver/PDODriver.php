<?php

namespace Modules\DBAL\Driver;

use Modules\DBAL\Driver;
use PDO;

abstract class PDODriver extends Driver
{
    /**
     * @var PDO
     */
    private $pdo;

    protected function connect($dsn, $username, $password, array $options = array())
    {
        $this->pdo = new PDO($dsn, $username, $password, $options);

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(
            PDO::ATTR_STATEMENT_CLASS,
            array(__NAMESPACE__ . '\\PDOStatement', array())
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

    public function query($query)
    {
        return $this->pdo->query($query);
    }

    public function prepare($query, array $options = array())
    {
        return $this->pdo->prepare($query, $options);
    }

    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    public function commit()
    {
        return $this->pdo->commit();
    }

    public function rollback()
    {
        return $this->pdo->rollback();
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
}

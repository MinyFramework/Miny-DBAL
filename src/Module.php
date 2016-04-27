<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL;

use DBTiny\Driver;
use Miny\Application\BaseApplication;
use Miny\Log\NullLog;

class Module extends \Miny\Modules\Module
{
    public function defaultConfiguration()
    {
        return [
            'log' => true,
            'startUpQueries' => []
        ];
    }


    public function init(BaseApplication $app)
    {
        if ($this->hasConfiguration('driver')) {
            $container = $app->getContainer();
            $container->addAlias(
                Driver::class,
                $this->getConfiguration('driver:class')
            );
            $startUpQueries = $this->getConfiguration('startUpQueries');
            $container->addCallback($this->getConfiguration('driver:class'), function (Driver $driver, $container) use ($startUpQueries) {
                foreach ((array)$startUpQueries as $query) {
                    $driver->query($query);
                }
            });
            $container->addConstructorArguments(
                $this->getConfiguration('driver:class'),
                $this->getConfiguration('log') ? null : $container->get(NullLog::class),
                $this->getConfiguration('driver:parameters'),
                $this->getConfiguration('driver:user'),
                $this->getConfiguration('driver:password'),
                $this->getConfiguration('driver:options')
            );
        }
    }
}

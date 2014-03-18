<?php

/**
 * This file is part of the Miny framework.
 * (c) Dániel Buga <daniel@bugadani.hu>
 *
 * For licensing information see the LICENSE file.
 */

namespace Modules\DBAL;

use Miny\Application\BaseApplication;

class Module extends \Miny\Modules\Module
{

    public function init(BaseApplication $app)
    {
        if ($this->hasConfiguration('driver')) {
            $app->getContainer()->addAlias(
                __NAMESPACE__ . '\\Driver',
                $this->getConfiguration('driver:class'),
                array(
                    $this->getConfiguration('driver:parameters'),
                    $this->getConfiguration('driver:user'),
                    $this->getConfiguration('driver:password'),
                    $this->getConfiguration('driver:options')
                )
            );
        }
    }
}

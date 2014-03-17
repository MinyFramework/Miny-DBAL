<?php

namespace Modules\DBAL;

abstract class Platform
{

    abstract public function getLimitAndOffset($limit, $offset);
}

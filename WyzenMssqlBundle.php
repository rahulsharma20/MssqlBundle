<?php

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wyzen\MssqlBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\DBAL\Types\Type;

class WyzenMssqlBundle extends Bundle
{
    public function boot()
    {
        // Register custom data types
        if(!Type::hasType('uniqueidentifier')) {
            Type::addType('uniqueidentifier', 'Wyzen\MssqlBundle\Types\UniqueidentifierType');
        }
        
        if(!Type::hasType('geography')) {
            Type::addType('geography', 'Wyzen\MssqlBundle\Types\PointType');
        }

        Type::overrideType('date', 'Wyzen\MssqlBundle\Types\DateType');        
        Type::overrideType('datetime', 'Wyzen\MssqlBundle\Types\DateTimeType');
    }
}

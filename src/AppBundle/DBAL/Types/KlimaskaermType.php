<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class KlimaskaermType extends AbstractEnumType
{
    const NONE = '';
    const KLIMASKAERM = 'klimaskaerm';
    const VINDUE = 'vindue';

    protected static $choices = [
        self::NONE => '',
        self::KLIMASKAERM => 'Klimaskaerm',
        self::VINDUE => 'Vindue',
    ];
}

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

class RisikovurderingType extends AbstractEnumType
{
    const LAV = 'lav';
    const MELLEM = 'mellem';
    const HOEJ = 'hoej';

    protected static $choices = [
        self::LAV => 'Lav',
        self::MELLEM => 'Mellem',
        self::HOEJ => 'Høj',
    ];
}

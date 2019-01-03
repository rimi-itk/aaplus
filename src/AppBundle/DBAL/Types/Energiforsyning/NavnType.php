<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DBAL\Types\Energiforsyning;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class NavnType extends AbstractEnumType
{
    const NONE = '';
    const FJERNVARME = 'fjernvarme';
    const HOVEDFORSYNING_EL = 'hovedforsyning_el';
    const OLIEFYR = 'oliefyr';
    const TRAEPILLEFYR = 'traepillefyr';
    const VARMEPUMPE = 'varmepumpe';

    protected static $choices = [
    self::NONE => '',
    self::FJERNVARME => 'Fjernvarme',
    self::HOVEDFORSYNING_EL => 'Hovedforsyning El',
    self::OLIEFYR => 'Oliefyr',
    self::TRAEPILLEFYR => 'Træpillefyr',
    self::VARMEPUMPE => 'Varmepumpe',
  ];
}

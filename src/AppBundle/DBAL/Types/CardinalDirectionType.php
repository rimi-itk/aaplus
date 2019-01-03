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

class CardinalDirectionType extends AbstractEnumType
{
    const NONE = '';
    const NORTH = 'north';
    const EAST = 'east';
    const SOUTH = 'south';
    const WEST = 'west';

    protected static $choices = [
    self::NONE => '',
    self::NORTH => 'North',
    self::EAST => 'East',
    self::SOUTH => 'South',
    self::WEST => 'West',
  ];
}

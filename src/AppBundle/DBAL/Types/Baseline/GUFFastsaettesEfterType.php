<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DBAL\Types\Baseline;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class GUFFastsaettesEfterType extends AbstractEnumType
{
    const GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL = 'guf_andel';
    const SAMLET_MAANEDSFORBRUG_FOR_JUNI_JULI_AUGUST = 'samlet_maanedsforbrug_for_juni_juli_august';

    protected static $choices = [
    self::GUF_ANDEL_I_PROCENT_PBA_ELO_NOEGLETAL => 'GUF-andel i procent pba ELO-nøgletal',
    self::SAMLET_MAANEDSFORBRUG_FOR_JUNI_JULI_AUGUST => 'Samlet månedsforbrug for juni, juli og august (hvis forbrug kendes)',
  ];
}

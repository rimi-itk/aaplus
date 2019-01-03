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

class ElKildeSekundaerType extends AbstractEnumType
{
    const INGEN_SEKUNDAER_KILDE_VURDERES_NOEDVENDIG = 'ingen_sekundaer_kilde_vurderes_noedvendig';
    const FORSYNINGSSELSKAB = 'forsyningsselskab';
    const SE_ELFORBRUG = 'se_elforbrug';
    const KEEPFOCUS_FJERNAFLAESNING = 'keepfocus_fjernaflaesning';
    const KEEPFOCUS_MANUEL_AFLAESNING = 'keepfocus_manuel_aflaesning';
    const SERVICELEDERE_DRIFTSPERSONALE = 'serviceledere_driftspersonale';
    const KMD_OPUS = 'kmd_opus';
    const MARK_KONTROL = 'mark_kontrol';

    protected static $choices = [
    self::INGEN_SEKUNDAER_KILDE_VURDERES_NOEDVENDIG => 'Ingen sekundær kilde vurderes nødvendig',
    self::FORSYNINGSSELSKAB => 'Forsyningsselskab',
    self::SE_ELFORBRUG => 'Se Elforbrug',
    self::KEEPFOCUS_FJERNAFLAESNING => 'KeepFocus (fjernaflæsning)',
    self::KEEPFOCUS_MANUEL_AFLAESNING => 'KeepFocus (manuel aflæsning)',
    self::SERVICELEDERE_DRIFTSPERSONALE => 'Serviceledere / driftspersonale',
    self::KMD_OPUS => 'KMD Opus',
    self::MARK_KONTROL => 'Mark-kontrol',
  ];
}

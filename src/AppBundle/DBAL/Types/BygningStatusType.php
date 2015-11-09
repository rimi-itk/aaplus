<?php
namespace AppBundle\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

class BygningStatusType extends AbstractEnumType
{

  const IKKE_STARTET  = '1_IS';
  const DATA_VERIFICERET = '2_DV';
  const TILKNYTTET_RAADGIVER  = '3_TR';
  const AFLEVERET_RAADGIVER = '4_AR';
  const AAPLUS_VERIFICERET  = '5_AV';
  const GODKENDT_AF_MAGISTRAT = '6_GM';
  const UNDER_UDFOERSEL = '7_UU';
  const DRIFT = '8_DR';

  protected static $choices = [
    self::IKKE_STARTET  => 'Ikke startet',
    self::DATA_VERIFICERET => 'Data verificeret',
    self::TILKNYTTET_RAADGIVER  => 'Tilknyttet Rådgiver',
    self::AFLEVERET_RAADGIVER => 'Afleveret af Rådgiver',
    self::AAPLUS_VERIFICERET  => 'AaPlus Verificeret',
    self::GODKENDT_AF_MAGISTRAT => 'Godkendt af magistrat',
    self::UNDER_UDFOERSEL => 'Under udførsel',
    self::DRIFT => 'Drift',
  ];
}
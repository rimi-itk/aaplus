<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BelysningTiltag.
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class KlimaskaermTiltag extends Tiltag
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // @Todo: Find af way to use the translations system or move this to some place else....
        $this->setTitle('Klimaskærm');
    }

    protected function calculateVarmebesparelseGAF($value = null)
    {
        $value = $this->sum('kWhBesparVarmevaerkEksternEnergikilde') * $this->getRapport()->getFaktorPaaVarmebesparelse();

        return parent::calculateVarmebesparelseGAF($value);
    }

    protected function calculateElbesparelse($value = null)
    {
        $value = $this->sum('kWhBesparElvaerkEksternEnergikilde');

        return parent::calculateElbesparelse($value);
    }

    protected function calculateSamletEnergibesparelse()
    {
        return $this->varmebesparelseGAF * $this->calculateVarmepris() + $this->elbesparelse * $this->getRapport()->getElKrKWh();
    }

    protected function calculateSamletCo2besparelse()
    {
        return (($this->varmebesparelseGAF / 1000) * $this->getRapport()->getVarmeKgCo2MWh()
      + ($this->elbesparelse / 1000) * $this->getRapport()->getElKgCo2MWh()) / 1000;
    }

    protected function calculateAnlaegsinvestering($value = null)
    {
        $value = $this->sum('samletInvesteringKr');

        return parent::calculateAnlaegsinvestering($value);
    }

    protected function calculateLevetid()
    {
        $denominator = $this->sum(function ($detail) {
            // AI
            return $detail->getEnhedsprisEksklMoms() * $detail->getArealM2();
        });
        if (0 === $denominator) {
            return 1;
        }

        return round($this->divide(
      $this->sum(function ($detail) {
          // AK
          if ($detail->getLevetidAar() > 0) {
              return $detail->getLevetidAar() * $detail->getEnhedsprisEksklMoms() * $detail->getArealM2();
          }

          return 0;
      }),
      $denominator
    ));
    }

    protected function calculateMaengde()
    {
        return $this->sum('arealM2');
    }

    protected function calculateEnhed()
    {
        return 'm2';
    }
}

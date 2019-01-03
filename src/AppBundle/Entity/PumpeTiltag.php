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
 * Tiltag.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PumpeTiltagRepository")
 */
class PumpeTiltag extends Tiltag
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // @Todo: Find af way to use the translations system or move this to some place else....
        $this->setTitle('Pumpeudskiftninger');
    }

    protected function calculateVarmebesparelseGAF($value = null)
    {
        $value = $this->sum('kwhBesparelseVarmeFraVaerket') * $this->getRapport()->getFaktorPaaVarmebesparelse();

        return parent::calculateVarmebesparelseGAF($value);
    }

    protected function calculateElbesparelse($value = null)
    {
        $value = $this->sum('kwhBesparelseElFraVaerket');

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
        $value = $this->sum('samletInvesteringInklPristillaeg');

        return parent::calculateAnlaegsinvestering($value);
    }

    protected function calculateMaengde()
    {
        return \count($this->getTilvalgteDetails());
    }

    protected function calculateEnhed()
    {
        return 'stk';
    }
}

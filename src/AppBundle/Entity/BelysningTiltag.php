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
 * @ORM\Entity(repositoryClass="AppBundle\Entity\BelysningTiltagRepository")
 */
class BelysningTiltag extends Tiltag
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // @Todo: Find af way to use the translations system or move this to some place else....
        $this->setTitle('Belysning');
    }

    protected function calculateVarmebesparelseGAF($value = null)
    {
        $value = $this->sum('kWhBesparelseVarmeFraVarmevaerket') * $this->getRapport()->getFaktorPaaVarmebesparelse();

        return parent::calculateVarmebesparelseGAF($value);
    }

    protected function calculateElbesparelse($value = null)
    {
        $value = $this->sum('kwhBesparelseEl');

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
        $value = $this->sum('investeringAlleLokalerKr');

        return parent::calculateAnlaegsinvestering($value);
    }

    protected function calculateReinvestering()
    {
        if ($this->levetid >= 15) {
            return 0;
        }

        return $this->faktorForReinvesteringer * $this->getAaplusInvestering();
    }

    protected function calculateBesparelseDriftOgVedligeholdelse()
    {
        return $this->sum('driftsbesparelseTilLyskilderKrAar');
    }

    protected function calculateLevetid()
    {
        return round($this->divide(
        $this->sum(function ($detail) { return $detail->getUdgiftSensorer() * $detail->getLevetidSensor(); }) + $this->sum('armaturvaegtning') + $this->sum('lyskildevaegtning'),
                               $this->sum('udgiftSensorer') + $this->sum('udgiftArmaturer') + $this->sum('udgiftLyskilde')
    ));
    }

    protected function calculateMaengde()
    {
        return $this->sum('rumstoerrelseM2');
    }

    protected function calculateEnhed()
    {
        return 'm2';
    }
}

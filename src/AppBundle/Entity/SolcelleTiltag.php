<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use AppBundle\Calculation\Calculation;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tiltag.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SolcelleTiltagRepository")
 */
class SolcelleTiltag extends Tiltag
{
    /**
     * @var float
     *
     * @ORM\Column(name="solcelleproduktion", type="decimal", scale=4, precision=14)
     */
    protected $solcelleproduktion;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // @Todo: Find af way to use the translations system or move this to some place else....
        $this->setTitle('Solceller');
    }

    public function calculate()
    {
        $this->solcelleproduktion = $this->calculateSolcelleproduktion();
        parent::calculate();
    }

    public function calculateSavingsForYear($year)
    {
        if ($year > $this->levetid) {
            return 0;
        }

        return parent::calculateSavingsForYear($year)
            + $this->getSolcelleproduktion() * $this->getRapport()->getElKrKWh($year)
            + $this->getSalgTilNettetAar1() * $this->getRapport()->getConfiguration()->getSolcelletiltagdetailSalgsprisFoerste10AarKrKWh();
    }

    public function getSolcelleproduktion()
    {
        return $this->solcelleproduktion;
    }

    public function getSalgTilNettetAar1()
    {
        return $this->sum(function ($detail) {
            return $detail->getCashFlow()['Salg til nettet'][1];
        }) / $this->getRapport()->getConfiguration()->getSolcelletiltagdetailSalgsprisFoerste10AarKrKWh();
    }

    protected function calculateSolcelleproduktion($value = null)
    {
        return $this->sum('egetForbrugAfProduktionenKWh');
    }

    protected function calculateElbesparelse($value = null)
    {
        return 0;
    }

    protected function calculateSamletEnergibesparelse()
    {
        return $this->solcelleproduktion * $this->getRapport()->getElKrKWh()
            + $this->getSalgTilNettetAar1() * $this->getRapport()->getConfiguration()->getSolcelletiltagdetailSalgsprisFoerste10AarKrKWh();
    }

    protected function calculateSamletCo2besparelse()
    {
        $forsyningsvaerk = $this->getRapport()->getBygning()->getForsyningsvaerkEl();
        $elKgCo2MWh = !$forsyningsvaerk ? 0 : $forsyningsvaerk->getKgCo2MWh(2009);

        return ($this->solcelleproduktion + $this->getSalgTilNettetAar1()) / 1000 * $elKgCo2MWh / 1000;
    }

    protected function calculateAnlaegsinvestering($value = null)
    {
        $value = ($this->sum('investeringKr') + $this->sum('screeningOgProjekteringKr'));

        return parent::calculateAnlaegsinvestering($value);
    }

    protected function calculateSimpelTilbagebetalingstidAar()
    {
        return $this->sum('simpelTilbagebetalingstidAar');
    }

    protected function calculateNutidsvaerdiSetOver15AarKr()
    {
        if (1 === $this->getTilvalgteDetails()->count()) {
            return Calculation::npv(
                $this->getRapport()->getKalkulationsrente(),
                $this->getTilvalgteDetails()->first()->getCashFlow()['Cash flow']
            );
        }

        return 0;
    }

    protected function calculateMaengde()
    {
        return $this->sum('anlaegsstoerrelseKWp');
    }

    protected function calculateEnhed()
    {
        return 'KWp';
    }
}

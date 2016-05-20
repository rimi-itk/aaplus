<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Entity;

use AppBundle\Calculation\Calculation;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;

/**
 * Tiltag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SolcelleTiltagRepository")
 */
class SolcelleTiltag extends Tiltag {

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();

    // @Todo: Find af way to use the translations system or move this to some place else....
    $this->setTitle('Solceller');
  }

  public function getSalgTilNettetAar1() {
    return $this->sum(function($detail) { return $detail->getCashFlow()['Salg til nettet'][1]; }) / $this->getRapport()->getConfiguration()->getSolcelletiltagdetailSalgsprisFoerste10AarKrKWh();
  }

  protected function calculateElbesparelse($value = null) {
    $value = $this->sum('egetForbrugAfProduktionenKWh');

    return parent::calculateElbesparelse($value);
  }

  protected function calculateSamletEnergibesparelse() {
    return ($this->elbesparelse * $this->getRapport()->getElKrKWh() + $this->sum(function($detail) { return $detail->getCashFlow()['Salg til nettet'][1]; }));
  }

  protected function calculateSamletCo2besparelse() {
    return (($this->varmebesparelseGAF / 1000) * $this->getRapport()->getVarmeKgCo2MWh()
            + ($this->elbesparelse / 1000) * $this->getRapport()->getElKgCo2MWh()) / 1000;
  }

  protected function calculateAnlaegsinvestering($value = NULL) {
    $value = ($this->sum('investeringKr') + $this->sum('screeningOgProjekteringKr'));

    return parent::calculateAnlaegsinvestering($value);
  }

  protected function calculateSimpelTilbagebetalingstidAar() {
    return $this->sum('simpelTilbagebetalingstidAar');
  }

  protected function calculateNutidsvaerdiSetOver15AarKr() {
    if ($this->getTilvalgteDetails()->count() == 1) {
      return Calculation::npv($this->getRapport()->getKalkulationsrente(), $this->getTilvalgteDetails()->first()->getCashFlow()['Cash flow']);
    }
    return 0;
  }

  protected function calculateMaengde() {
    return $this->sum('anlaegsstoerrelseKWp');
  }

  protected function calculateEnhed() {
    return 'KWp';
  }

}

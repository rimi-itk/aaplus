<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Entity;

use AppBundle\Annotations\Calculated;
use AppBundle\Calculation\Calculation;
use AppBundle\DBAL\Types\Energiforsyning\NavnType;
use AppBundle\DBAL\Types\Energiforsyning\InternProduktion\PrisgrundlagType;
use AppBundle\Entity\Energiforsyning\InternProduktion;
use AppBundle\Entity\Energiforsyning;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\Event\LifecycleEventArgs;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Blameable\Traits\BlameableEntity;
use PHPExcel_Calculation_Financial as Excel;

/**
 * Rapport
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RapportRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Rapport {

  use BlameableEntity;
  use TimestampableEntity;

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @OneToOne(targetEntity="Bygning", inversedBy="rapport", fetch="EAGER")
   **/
  protected $bygning;

  /**
   * @OneToMany(targetEntity="Energiforsyning", mappedBy="rapport", cascade={"persist", "remove"})
   * @OrderBy({"id" = "ASC"})
   * @JMS\Type("Doctrine\Common\Collections\ArrayCollection<AppBundle\Entity\Energiforsyning>")
   */
  protected $energiforsyninger;

  /**
   * @OneToMany(targetEntity="Bilag", mappedBy="rapport")
   * @OrderBy({"id" = "ASC"})
   * @JMS\Type("Doctrine\Common\Collections\ArrayCollection<AppBundle\Entity\Bilag>")
   */
  protected $bilag;

  /**
   * @OneToMany(targetEntity="Tiltag", mappedBy="rapport", cascade={"persist", "remove"})
   * @OrderBy({"id" = "ASC"})
   * @JMS\Type("Doctrine\Common\Collections\ArrayCollection<AppBundle\Entity\Tiltag>")
   */
  protected $tiltag;

  /**
   * @var string
   *
   * @ORM\Column(name="version", type="string", length=255)
   */
  protected $version;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="Datering", type="date")
   */
  protected $datering;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="besparelseAarEt", type="float", scale=4, nullable=true)
   */
  protected $besparelseAarEt;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="fravalgtbesparelseAarEt", type="float", scale=4, nullable=true)
   */
  protected $fravalgtBesparelseAarEt;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="besparelseVarmeGUF", type="float", scale=4, nullable=true)
   */
  protected $besparelseVarmeGUF;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="fravalgtBesparelseVarmeGUF", type="float", scale=4, nullable=true)
   */
  protected $fravalgtBesparelseVarmeGUF;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="besparelseVarmeGAF", type="float", nullable=true)
   */
  protected $besparelseVarmeGAF;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="fravalgtBesparelseVarmeGAF", type="float", nullable=true)
   */
  protected $fravalgtBesparelseVarmeGAF;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="besparelseCO2", type="float", nullable=true)
   */
  protected $besparelseCO2;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="fravalgtBesparelseCO2", type="float", nullable=true)
   */
  protected $fravalgtBesparelseCO2;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="besparelseEl", type="float", nullable=true)
   */
  protected $besparelseEl;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="fravalgtBesparelseEl", type="float", nullable=true)
   */
  protected $fravalgtBesparelseEl;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="co2BesparelseEl", type="float", nullable=true)
   */
  protected $co2BesparelseEl;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="co2BesparelseVarme", type="float", nullable=true)
   */
  protected $co2BesparelseVarme;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="BaselineCO2El", type="float", nullable=true)
   */
  protected $BaselineCO2El;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="BaselineCO2Varme", type="float", nullable=true)
   */
  protected $BaselineCO2Varme;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="BaselineCO2Samlet", type="float", nullable=true)
   */
  protected $BaselineCO2Samlet;


  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="co2BesparelseElProcent", type="float", nullable=true)
   */
  protected $co2BesparelseElProcent;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="co2BesparelseVarmeProcent", type="float", nullable=true)
   */
  protected $co2BesparelseVarmeProcent;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="besparelseCO2Procent", type="float", nullable=true)
   */
  protected $besparelseCO2Procent;

  /**
   * @var float
   *
   * @ORM\Column(name="BaselineEl", type="decimal", scale=4, nullable=true)
   */
  protected $BaselineEl;

  /**
   * @var float
   *
   * @ORM\Column(name="BaselineVarmeGUF", type="decimal", scale=4, nullable=true)
   */
  protected $BaselineVarmeGUF;

  /**
   * @var float
   *
   * @ORM\Column(name="BaselineVarmeGAF", type="decimal", scale=4, nullable=true)
   */
  protected $BaselineVarmeGAF;

  /**
   * @var float
   *
   * @ORM\Column(name="BaselineVand", type="decimal", scale=4, nullable=true)
   */
  protected $BaselineVand;

  /**
   * @var float
   *
   * @ORM\Column(name="BaselineStrafAfkoeling", type="decimal", scale=4, nullable=true)
   */
  protected $BaselineStrafAfkoeling;

  /**
   * @var float
   *
   * @ORM\Column(name="faktorPaaVarmebesparelse", type="decimal", scale=4, nullable=true)
   */
  protected $faktorPaaVarmebesparelse;

  /**
   * @var float
   *
   * @ORM\Column(name="energiscreening", type="decimal", scale=4, nullable=true)
   */
  protected $energiscreening;

  /**
   * Tilvalgt TotalInvestering = sum af alle tiltags anlægsinvesteringer
   *
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="anlaegsinvestering", type="float", nullable=true)
   */
  protected $anlaegsinvestering;

  /**
   * Fravlgt TotalInvestering = sum af alle tiltags anlægsinvesteringer
   *
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="fravalgtAnlaegsinvestering", type="float", nullable=true)
   */
  protected $fravalgtAnlaegsinvestering;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="nutidsvaerdiSetOver15AarKr", type="float", nullable=true)
   */
  protected $nutidsvaerdiSetOver15AarKr;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="fravalgtNutidsvaerdiSetOver15AarKr", type="float", nullable=true)
   */
  protected $fravalgtNutidsvaerdiSetOver15AarKr;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="mtmFaellesomkostninger", type="float", nullable=true)
   */
  protected $mtmFaellesomkostninger;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="implementering", type="float", nullable=true)
   */
  protected $implementering;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="fravalgtimplementering", type="float", nullable=true)
   */
  protected $fravalgtImplementering;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="faellesomkostninger", type="float", nullable=true)
   */
  protected $faellesomkostninger;

  /**
   * @var float
   *
   * @Calculated
   * @ORM\Column(name="internRenteInklFaellesomkostninger", type="float", nullable=true)
   */
  protected $internRenteInklFaellesomkostninger;

  /**
   * @var integer
   *
   * @ORM\Column(name="laanLoebetid", type="integer", nullable=true)
   */
  protected $laanLoebetid = 15;

  /**
   * @var boolean
   *
   * @ORM\Column(name="elena", type="boolean", nullable=true)
   */
  protected $elena = FALSE;

  /**
   * @var array of float
   *
   * @Calculated
   * @ORM\Column(name="cashFlow15", type="array")
   */
  protected $cashFlow15;

  /**
   * @var array of float
   *
   * @Calculated
   * @ORM\Column(name="cashFlow30", type="array")
   */
  protected $cashFlow30;

  /**
   * @return float
   */
  public function getBaselineCO2El() {
    return $this->BaselineCO2El;
  }

  /**
   * @param float $BaselineCO2El
   */
  public function setBaselineCO2El($BaselineCO2El) {
    $this->BaselineCO2El = $BaselineCO2El;
  }

  /**
   * @return float
   */
  public function getBaselineCO2Varme() {
    return $this->BaselineCO2Varme;
  }

  /**
   * @param float $BaselineCO2Varme
   */
  public function setBaselineCO2Varme($BaselineCO2Varme) {
    $this->BaselineCO2Varme = $BaselineCO2Varme;
  }

  /**
   * @return float
   */
  public function getBaselineCO2Samlet() {
    return $this->BaselineCO2Samlet;
  }

  /**
   * @param float $BaselineCO2Samlet
   */
  public function setBaselineCO2Samlet($BaselineCO2Samlet) {
    $this->BaselineCO2Samlet = $BaselineCO2Samlet;
  }

  /**
   * @return float
   */
  public function getCo2BesparelseElProcent() {
    return $this->co2BesparelseElProcent;
  }

  /**
   * @param float $co2BesparelseElProcent
   */
  public function setCo2BesparelseElProcent($co2BesparelseElProcent) {
    $this->co2BesparelseElProcent = $co2BesparelseElProcent;
  }

  /**
   * @return float
   */
  public function getCo2BesparelseVarmeProcent() {
    return $this->co2BesparelseVarmeProcent;
  }

  /**
   * @param float $co2BesparelseVarmeProcent
   */
  public function setCo2BesparelseVarmeProcent($co2BesparelseVarmeProcent) {
    $this->co2BesparelseVarmeProcent = $co2BesparelseVarmeProcent;
  }

  /**
   * @return float
   */
  public function getBesparelseCO2Procent() {
    return $this->besparelseCO2Procent;
  }

  /**
   * @param float $besparelseCO2Procent
   */
  public function setBesparelseCO2Procent($besparelseCO2Procent) {
    $this->besparelseCO2Procent = $besparelseCO2Procent;
  }



  /**
   * Get cashFlow15
   *
   * @return array of float
   */
  public function getCashFlow15() {
    return $this->cashFlow15;
  }

  /**
   * Get cashFlow30
   *
   * @return array of float
   */
  public function getCashFlow30() {
    return $this->cashFlow30;
  }

  /**
   * @var Forsyningsvaerk
   */
  protected $traepillefyr;

  public function setTraepillefyr(Forsyningsvaerk $traepillefyr = NULL) {
    $this->traepillefyr = $traepillefyr;

    return $this;
  }

  public function getTraepillefyr() {
    return $this->traepillefyr;
  }

  /**
   * @var array
   */
  protected $cashFlow;

  /**
   * Constructor
   */
  public function __construct() {
    $this->tiltag = new \Doctrine\Common\Collections\ArrayCollection();
    $this->energiforsyninger = new \Doctrine\Common\Collections\ArrayCollection();
    $this->bilag = new \Doctrine\Common\Collections\ArrayCollection();
    $this->datering = new \DateTime();
    $this->version = 1;
  }

  /**
   * Get Name
   *
   * @return string
   */
  public function __toString() {
    return $this->getBygning()->getAdresse() . ", " . $this->version;
  }

  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    return $this->id;
  }

  /**
   * Set version
   *
   * @param string $version
   * @return Rapport
   */
  public function setVersion($version) {
    $this->version = $version;

    return $this;
  }

  /**
   * Get version
   *
   * @return string
   */
  public function getVersion() {
    return $this->version;
  }

  /**
   * Get the "full" version with nummeric building status appended
   *
   * @return string
   */
  public function getFullVersion() {
    return $this->getBygning()->getNummericStatus() . '.' . $this->version;
  }

  /**
   * Set datering
   *
   * @param \DateTime $datering
   * @return Rapport
   */
  public function setDatering($datering) {
    $this->datering = $datering;

    return $this;
  }

  /**
   * Get datering
   *
   * @return \DateTime
   */
  public function getDatering() {
    return $this->datering;
  }


  /**
   * Set bygning
   *
   * @param \AppBundle\Entity\Bygning $bygning
   * @return Rapport
   */
  public function setBygning(\AppBundle\Entity\Bygning $bygning = NULL) {
    $this->bygning = $bygning;

    return $this;
  }

  /**
   * Get bygning
   *
   * @return \AppBundle\Entity\Bygning
   */
  public function getBygning() {
    return $this->bygning;
  }

  /**
   * Add energiforsyning
   *
   * @param \AppBundle\Entity\Energiforsyning $energiforsyning
   * @return Rapport
   */
  public function addEnergiforsyning(\AppBundle\Entity\Energiforsyning $energiforsyning) {
    $this->energiforsyninger[] = $energiforsyning;

    $energiforsyning->setRapport($this);

    return $this;
  }

  /**
   * Remove energiforsyning
   *
   * @param \AppBundle\Entity\Energiforsyning $energiforsyning
   */
  public function removeEnergiforsyning(\AppBundle\Entity\Energiforsyning $energiforsyning) {
    $this->energiforsyninger->removeElement($energiforsyning);
  }

  /**
   * Set energiforsyninger
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function setEnergiforsyninger($energiforsyninger) {
    $this->energiforsyninger = $energiforsyninger;

    return $this;
  }

  /**
   * Get energiforsyninger
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getEnergiforsyninger() {
    return $this->energiforsyninger;
  }

  /**
   * Add bilag
   *
   * @param \AppBundle\Entity\Bilag $bilag
   * @return Rapport
   */
  public function addBilag(\AppBundle\Entity\Bilag $bilag) {
    $this->bilag[] = $bilag;

    $bilag->setRapport($this);

    return $this;
  }

  /**
   * Remove bilag
   *
   * @param \AppBundle\Entity\Bilag $bilag
   */
  public function removeBilag(\AppBundle\Entity\Bilag $bilag) {
    $this->bilag->removeElement($bilag);
  }

  /**
   * Set bilag
   *
   * @return Rapport
   */
  public function setBilag($bilag) {
    $this->bilag = $bilag;

    return $this;
  }

  /**
   * Get bilag
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getBilag() {
    return $this->bilag;
  }


  /**
   * Add tiltag
   *
   * @param \AppBundle\Entity\Tiltag $tiltag
   * @return Rapport
   */
  public function addTiltag(\AppBundle\Entity\Tiltag $tiltag) {
    $this->tiltag[] = $tiltag;

    $tiltag->setRapport($this);

    return $this;
  }

  /**
   * Remove tiltag
   *
   * @param \AppBundle\Entity\Tiltag $tiltag
   */
  public function removeTiltag(\AppBundle\Entity\Tiltag $tiltag) {
    $this->tiltag->removeElement($tiltag);
  }

  /**
   * Get tiltag
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getTiltag() {
    return $this->tiltag;
  }

  /**
   * Get all selected Tiltag.
   *
   * @return \Doctrine\Common\Collections\Collection
   *   The list of selected TiltagDetails.
   */
  public function getTilvalgteTiltag() {
    return $this->getTiltag()->filter(function ($tiltag) {
      return $tiltag->getTilvalgt();
    });
  }

  /**
   * Get all deselected Tiltag.
   *
   * @return \Doctrine\Common\Collections\Collection
   *   The list of selected TiltagDetails.
   */
  public function getFravalgteTiltag() {
    return $this->getTiltag()->filter(function ($tiltag) {
      return !$tiltag->getTilvalgt();
    });
  }

  /**
   * Get total besparelseVarme
   *
   * @return float
   */
  public function getBesparelseAarEt() {
    return $this->besparelseAarEt;
  }

  /**
   * Get total besparelseVarme for fravalgte tiltag
   *
   * @return float
   */
  public function getFravalgtBesparelseAarEt() {
    return $this->fravalgtBesparelseAarEt;
  }

  /**
   * Get total besparelseVarme
   *
   * @return float
   */
  public function getBesparelseVarme() {
    return $this->besparelseVarmeGUF + $this->besparelseVarmeGAF;
  }

  /**
   * Get total besparelseVarme for fravalgte tiltag
   *
   * @return float
   */
  public function getFravalgtBesparelseVarme() {
    return $this->fravalgtBesparelseVarmeGUF + $this->fravalgtBesparelseVarmeGAF;
  }

  /**
   * Get besparelseVarmeGUF
   *
   * @return float
   */
  public function getBesparelseVarmeGUF() {
    return $this->besparelseVarmeGUF;
  }

  /**
   * Get besparelseVarmeGUF for fravalgte tiltag
   *
   * @return float
   */
  public function getFravalgtBesparelseVarmeGUF() {
    return $this->fravalgtBesparelseVarmeGUF;
  }

  /**
   * Get besparelseVarmeGAF
   *
   * @return float
   */
  public function getBesparelseVarmeGAF() {
    return $this->besparelseVarmeGAF;
  }

  /**
   * Get besparelseVarmeGAF for fravalgte tiltag
   *
   * @return float
   */
  public function getFravalgtBesparelseVarmeGAF() {
    return $this->fravalgtBesparelseVarmeGAF;
  }

  /**
   * Get besparelseCO2
   *
   * @return float
   */
  public function getBesparelseCO2() {
    return $this->besparelseCO2;
  }

  /**
   * Get besparelseCO2 for fravalgte tiltag
   *
   * @return float
   */
  public function getFravalgtBesparelseCO2() {
    return $this->fravalgtBesparelseCO2;
  }

  /**
   * Get anlaegsinvestering
   *
   * @return string
   */
  public function getAnlaegsinvestering() {
    return $this->anlaegsinvestering;
  }

  /**
   * Get anlaegsinvestering
   *
   * @return string
   */
  public function getFravalgtAnlaegsinvestering() {
    return $this->fravalgtAnlaegsinvestering;
  }

  /**
   * Get Sum af økonomi for tilvalgte tiltag
   */
  public function getTilvalgtSum() {
    return $this->getInvesteringEkslGenopretningOgModernisering() + $this->energiscreening + $this->mtmFaellesomkostninger + $this->implementering;
  }

  /**
   * Get Sum af økonomi for fravalgte tiltag
   */
  public function getFravalgtSum() {
    return $this->getFravalgtInvesteringEkslGenopretningOgModernisering() + $this->getFravalgtImplementering();
  }

  /**
   * Get besparelseEl
   *
   * @return float
   */
  public function getBesparelseEl() {
    return $this->besparelseEl;
  }

  /**
   * Get co2besparelseEl
   *
   * @return float
   */
  public function getCo2BesparelseEl() {
    return $this->co2BesparelseEl;
  }

  /**
   * Get co2besparelseVarme
   *
   * @return float
   */
  public function getCo2BesparelseVarme() {
    return $this->co2BesparelseVarme;
  }

  /**
   * Get besparelseEl for fravalgte tiltag
   *
   * @return float
   */
  public function getFravalgtBesparelseEl() {
    return $this->fravalgtBesparelseEl;
  }

  /**
   * Set BaselineEl
   *
   * @param integer $baselineEl
   * @return Rapport
   */
  public function setBaselineEl($baselineEl) {
    $this->BaselineEl = $baselineEl;

    return $this;
  }

  /**
   * Get BaselineEl
   *
   * @return integer
   */
  public function getBaselineEl() {
    return $this->BaselineEl;
  }

  /**
   * Set BaselineVarmeGUF
   *
   * @param integer $baselineVarmeGUF
   * @return Rapport
   */
  public function setBaselineVarmeGUF($baselineVarmeGUF) {
    $this->BaselineVarmeGUF = $baselineVarmeGUF;

    return $this;
  }

  /**
   * Get BaselineVarmeGUF
   *
   * @return integer
   */
  public function getBaselineVarmeGUF() {
    return $this->BaselineVarmeGUF;
  }

  /**
   * Set BaselineVarmeGAF
   *
   * @param integer $baselineVarmeGAF
   * @return Rapport
   */
  public function setBaselineVarmeGAF($baselineVarmeGAF) {
    $this->BaselineVarmeGAF = $baselineVarmeGAF;

    return $this;
  }

  /**
   * Get BaselineVarmeGAF
   *
   * @return integer
   */
  public function getBaselineVarmeGAF() {
    return $this->BaselineVarmeGAF;
  }

  /**
   * Get BaselineVarme
   *
   * @return integer
   */
  public function getBaselineVarme() {
    return $this->BaselineVarmeGAF + $this->BaselineVarmeGUF;
  }

  /**
   * Set BaselineVand
   *
   * @param integer $baselineVand
   * @return Rapport
   */
  public function setBaselineVand($baselineVand) {
    $this->BaselineVand = $baselineVand;

    return $this;
  }

  /**
   * Get BaselineVand
   *
   * @return integer
   */
  public function getBaselineVand() {
    return $this->BaselineVand;
  }

  /**
   * Set BaselineStrafAfkoeling
   *
   * @param integer $baselineStrafAfkoeling
   * @return Rapport
   */
  public function setBaselineStrafAfkoeling($baselineStrafAfkoeling) {
    $this->BaselineStrafAfkoeling = $baselineStrafAfkoeling;

    return $this;
  }

  /**
   * Get BaselineStrafAfkoeling
   *
   * @return integer
   */
  public function getBaselineStrafAfkoeling() {
    return $this->BaselineStrafAfkoeling;
  }

  /**
   * Set faktorPaaVarmebesparelse
   *
   * @param float $faktorPaaVarmebesparelse
   * @return Rapport
   */
  public function setFaktorPaaVarmebesparelse($faktorPaaVarmebesparelse) {
    $this->faktorPaaVarmebesparelse = $faktorPaaVarmebesparelse;

    return $this;
  }

  /**
   * Get faktorPaaVarmebesparelse
   *
   * @return float
   */
  public function getFaktorPaaVarmebesparelse() {
    return $this->faktorPaaVarmebesparelse;
  }

  /**
   * Set energiscreening
   *
   * @param integer $energiscreening
   * @return Rapport
   */
  public function setEnergiscreening($energiscreening) {
    $this->energiscreening = $energiscreening;

    return $this;
  }

  /**
   * Get Energiscreening
   *
   * @return integer
   */
  public function getEnergiscreening() {
    return $this->energiscreening;
  }

  /**
   * Set laanLoebetid
   *
   * @param integer $laanLoebetid
   * @return Rapport
   */
  public function setLaanLoebetid($laanLoebetid) {
    $this->laanLoebetid = $laanLoebetid;

    return $this;
  }

  /**
   * Get LaanLoebetid
   *
   * @return integer
   */
  public function getLaanLoebetid() {
    return $this->laanLoebetid;
  }

  /**
   * Get nutidsvaerdiSetOver15AarKr.
   *
   * @return float
   */
  public function getNutidsvaerdiSetOver15AarKr() {
    return $this->nutidsvaerdiSetOver15AarKr;
  }

  /**
   * Get nutidsvaerdiSetOver15AarKr for fravlgte tiltag
   *
   * @return float
   */
  public function getFravalgtNutidsvaerdiSetOver15AarKr() {
    return $this->fravalgtNutidsvaerdiSetOver15AarKr;
  }

  /**
   * Set elena
   *
   * @param string $elena
   * @return Bygning
   */
  public function setElena($elena) {
    $this->elena = $elena;

    return $this;
  }

  /**
   * @var string
   *
   * @ORM\Column(name="Genopretning", type="decimal", nullable=true)
   */
  protected $genopretning;

  /**
   * @var string
   *
   * @ORM\Column(name="Modernisering", type="decimal", nullable=true)
   */
  protected $modernisering;

  /**
   * @var string
   *
   * @ORM\Column(name="FravalgtGenopretning", type="decimal", nullable=true)
   */
  protected $fravalgtGenopretning;

  /**
   * @var string
   *
   * @ORM\Column(name="FravalgtModernisering", type="decimal", nullable=true)
   */
  protected $fravalgtModernisering;

  /**
   * Get genopretning
   *
   * @return string
   */
  public function getGenopretning() {
    return $this->genopretning;
  }

  /**
   * Get modernisering
   *
   * @return string
   */
  public function getModernisering() {
    return $this->modernisering;
  }

  /**
   * Get genopretning for fravalgte tiltag
   *
   * @return string
   */
  public function getFravalgtGenopretning() {
    return $this->fravalgtGenopretning;
  }

  /**
   * Get moderniseringfor fravalgte tiltag
   *
   * @return string
   */
  public function getFravalgtModernisering() {
    return $this->fravalgtModernisering;
  }

  /**
   * Get elena
   *
   * @return boolean
   */
  public function getElena() {
    return $this->elena;
  }

  /**
   * Get Energiscreening
   *
   * @return integer
   */
  public function getTotalVarme() {
    return $this->getBaselineVarmeGAF() + $this->getBaselineVarmeGUF();
  }

  /**
   *
   * @return float
   */
  public function getKalkulationsrente() {
    return $this->configuration->getRapportKalkulationsrente();
  }

  /**
   *
   * @return float
   */
  public function getDriftomkostningerfaktor() {
    return $this->configuration->getRapportDriftomkostningerfaktor();
  }

  /**
   * Get interne driftomkostninger
   * (Forventet timeforbrug for lokalpersonalet)
   *
   * @return float
   */
  public function getInterneDriftomkostninger() {
    return $this->getDriftomkostningerfaktor() + (25 * $this->getBygning()->getBruttoetageareal());
  }

  /**
   *
   * @return float
   */
  public function getInflationsfaktor() {
    $kalkulationsrente = $this->getKalkulationsrente();
    $inflation = $this->getInflation();

    $inflationsfaktor = 0;
    for ($year = 1; $year <= 15; $year++) {
      $inflationsfaktor += pow(1 + $inflation, $year) / pow(1 + $kalkulationsrente, $year);
    }

    return $inflationsfaktor;
  }

  /**
   *
   * @return float
   */
  public function getInflation() {
    return $this->configuration->getRapportInflation();
  }

  /**
   *
   * @return float
   */
  public function getLobetid() {
    return $this->configuration->getRapportLobetid();
  }

  /**
   *
   * @return float
   */
  public function getProcentAfInvestering() {
    return $this->configuration->getRapportProcentAfInvestering();
  }

  /**
   *
   * @return float
   */
  public function getElfaktor() {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkEl();
    return !$forsyningsvaerk ? 0 : $forsyningsvaerk->getFaktor($this->configuration, $this->getDatering()->format('Y'));
  }

  /**
   *
   * @return float
   */
  public function getVarmefaktor() {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkVarme();
    return !$forsyningsvaerk ? 0 : $forsyningsvaerk->getFaktor($this->configuration, $this->getDatering()->format('Y'));
  }

  /**
   *
   * @return float
   */
  public function getVandfaktor() {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkVand();
    return !$forsyningsvaerk ? 0 : $forsyningsvaerk->getFaktor($this->configuration, $this->getDatering()->format('Y'));
  }

  /**
   *
   * @return float
   */
  public function getVandKrKWh($yearNumber = 1) {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkVand();
    return !$forsyningsvaerk ? 0 : $forsyningsvaerk->getKrKWh($this->getDatering()->format('Y') - 1 + $yearNumber);
  }

  /**
   *
   * @return float
   */
  public function getVarmeKrKWh($yearNumber = 1) {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkVarme();
    return !$forsyningsvaerk ? 0 : $forsyningsvaerk->getKrKWh($this->getDatering()->format('Y') - 1 + $yearNumber);
  }

  /**
   *
   * @return float
   */
  public function getElKrKWh($yearNumber = 1) {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkEl();
    return !$forsyningsvaerk ? 0 : $forsyningsvaerk->getKrKWh($this->getDatering()->format('Y') - 1 + $yearNumber);
  }

  /**
   *
   * @return float
   */
  public function getVarmeKgCo2MWh($yearNumber = 1) {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkVarme();
    return !$forsyningsvaerk ? 0 : $forsyningsvaerk->getKgCo2MWh($this->getDatering()->format('Y') - 1 + $yearNumber);
  }

  /**
   *
   * @return float
   */
  public function getElKgCo2MWh($yearNumber = 1) {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkEl();
    return !$forsyningsvaerk ? 0 : $forsyningsvaerk->getKgCo2MWh($this->getDatering()->format('Y') - 1 + $yearNumber);
  }

  /**
   *
   * @return boolean
   */
  public function getStandardforsyning() {
    if (!$this->energiforsyninger || $this->energiforsyninger->count() != 2) {
      return FALSE;
    }

    $energiforsyningEl = $this->getEnergiforsyningByNavn(NavnType::HOVEDFORSYNING_EL);
    $energiforsyningVarme = $this->getEnergiforsyningByNavn(NavnType::FJERNVARME);

    if (!$energiforsyningEl || !$energiforsyningVarme) {
      return FALSE;
    }

    $internProduktionEl = $energiforsyningEl->getInternProduktioner() ? $energiforsyningEl->getInternProduktioner()
      ->first() : NULL;
    $internProduktionVarme = $energiforsyningVarme->getInternProduktioner() ? $energiforsyningVarme->getInternProduktioner()
      ->first() : NULL;

    if ($internProduktionEl && $internProduktionVarme) {
      return $internProduktionEl->getPrisgrundlag() == PrisgrundlagType::EL && $internProduktionEl->getFordeling() == 1 && $internProduktionEl->getEffektivitet() == 1
      && $internProduktionVarme->getPrisgrundlag() == PrisgrundlagType::VARME && $internProduktionVarme->getFordeling() == 1 && $internProduktionVarme->getEffektivitet() == 1;
    }

    return FALSE;
  }

  public function getEnergiforsyningByNavn($navn) {
    if (!$this->energiforsyninger) {
      return NULL;
    }
    return $this->energiforsyninger->filter(function ($energiforsyning) use ($navn) {
      return $energiforsyning->getNavn() == $navn;
    })->first();
  }

  /**
   * @var Configuration
   */
  protected $configuration;

  public function setConfiguration(Configuration $configuration) {
    $this->configuration = $configuration;

    return $this;
  }

  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   *
   * @return float
   */
  public function getMtmFaellesomkostninger() {
    return $this->mtmFaellesomkostninger;
  }

  /**
   *
   * @return float
   */
  public function getImplementering() {
    return $this->implementering;
  }

  /**
   *
   * @return float
   */
  public function getFravalgtImplementering() {
    return $this->fravalgtImplementering;
  }

  /**
   *
   * @return float
   */
  public function getInternRenteInklFaellesomkostninger() {
    return $this->internRenteInklFaellesomkostninger;
  }

  /**
   *
   * @return float
   */
  public function getFaellesomkostninger() {
    return $this->faellesomkostninger;
  }

  /**
   *
   * @return array
   */
  public function getCashFlow() {
    return $this->cashFlow;
  }

  /**
   * Get investering eksl. genopretning og modernisering
   */
  public function getInvesteringEkslGenopretningOgModernisering() {
    return $this->anlaegsinvestering - ($this->genopretning + $this->modernisering);
  }

  /**
   * Get investering eksl. genopretning og modernisering
   */
  public function getFravalgtInvesteringEkslGenopretningOgModernisering() {
    return $this->fravalgtAnlaegsinvestering - ($this->fravalgtGenopretning + $this->fravalgtModernisering);
  }

  /**
   * Get investering eksl. øvrige omkostninger
   *
   * (Aa+ Investering eks. Øvrige omkostninger)
   */
  public function getinvesteringEksFaellesomkostninger() {
    return $this->getAnlaegsinvestering() - ($this->getModernisering() + $this->getGenopretning());
  }

  /**
   * Get investering inkl. genopretning og modernisering
   *
   * (Aa+ Investering inkl. Øvrige omkostninger)
   */
  public function getinvesteringInklFaellesomkostninger() {
    return $this->getInvesteringEksFaellesomkostninger() - ($this->getEnergiscreening() + $this->getMtmFaellesomkostninger() + $this->getImplementering());
  }

  /**
   * Get investering eksl. genopretning og modernisering for fravalgte tiltag
   */
  public function getFravalgtInvesteringEksFaellesomkostninger() {
    return $this->getFravalgtAnlaegsinvestering();
  }

  /**
   * Get sum fællesomkostninger
   */
  public function getSumFaellesOmkostninger() {
    return $this->mtmFaellesomkostninger + $this->implementering;
  }


  /**
   * Post load handler.
   *
   * @ORM\PostLoad
   * @param \Doctrine\ORM\Event\LifecycleEventArgs $event
   */
  public function postLoad(LifecycleEventArgs $event) {
    $repository = $event->getEntityManager()
      ->getRepository('AppBundle:Configuration');
    $this->setConfiguration($repository->getConfiguration());
  }

  /**
   * Post persist handler.
   *
   * @ORM\PostPersist
   * @param LifecycleEventArgs $event
   */
  public function postPersist(LifecycleEventArgs $event) {
    // Init with preset energiforsyning El
    $forsyningEl = new Energiforsyning();
    $forsyningEl
      ->setNavn(NavnType::HOVEDFORSYNING_EL)
      ->setBeskrivelse("El");
    $internProduktionEl = new InternProduktion();
    $internProduktionEl
      ->setNavn("El")
      ->setFordeling(1.0)
      ->setEffektivitet(1.0)
      ->setPrisgrundlag(PrisgrundlagType::EL);
    $forsyningEl->addInternProduktion($internProduktionEl);
    $forsyningEl->calculate();

    // Init with preset energiforsyning Varme
    $forsyningVarme = new Energiforsyning();
    $forsyningVarme
      ->setNavn(NavnType::FJERNVARME)
      ->setBeskrivelse("Fjernvarme");
    $internProduktionVarme = new InternProduktion();
    $internProduktionVarme
      ->setNavn("Varme")
      ->setFordeling(1.0)
      ->setEffektivitet(1.0)
      ->setPrisgrundlag(PrisgrundlagType::VARME);
    $forsyningVarme->addInternProduktion($internProduktionVarme);
    $forsyningVarme->calculate();

    $this->addEnergiforsyning($forsyningEl);
    $this->addEnergiforsyning($forsyningVarme);
    $event->getEntityManager()->flush();
  }

  public function calculate() {
    $this->besparelseVarmeGUF = $this->calculateBesparelseVarmeGUF();
    $this->fravalgtBesparelseVarmeGUF = $this->calculateFravalgtBesparelseVarmeGUF();
    $this->besparelseVarmeGAF = $this->calculateBesparelseVarmeGAF();
    $this->fravalgtBesparelseVarmeGAF = $this->calculateFravalgtBesparelseVarmeGAF();

    $this->besparelseCO2 = $this->calculateBesparelseCO2();
    $this->fravalgtBesparelseCO2 = $this->calculateFravalgtBesparelseCO2();
    $this->co2BesparelseEl = $this->calculateCo2BesparelseEl();
    $this->co2BesparelseVarme = $this->calculateCo2BesparelseVarme();

    $this->BaselineCO2El = $this->calculateBaselineCO2El();
    $this->BaselineCO2Varme = $this->calculateBaselineCO2Varme();
    $this->BaselineCO2Samlet = $this->calculateBaselineCO2Samlet();

    $this->co2BesparelseElProcent = $this->calculateCO2BesparelseElProcent();
    $this->co2BesparelseVarmeProcent = $this->calculateCO2BesparelseVarmeProcent();
    $this->co2BesparelseSamletProcent = $this->calculateCO2BesparelseSamletProcent();

    $this->besparelseEl = $this->calculateBesparelseEl();
    $this->fravalgtBesparelseEl = $this->calculateFravalgtBesparelseEl();

    $this->mtmFaellesomkostninger = $this->calculateMtmFaellesomkostninger();
    $this->implementering = $this->calculateImplementering();
    $this->fravalgtImplementering = $this->calculateFravalgtImplementering();
    $this->faellesomkostninger = $this->calculateFaellesomkostninger();

    $this->cashFlow = $this->calculateCashFlow();
    $this->besparelseAarEt = $this->calculateSavingsYearOne();
    $this->fravalgtBesparelseAarEt = $this->calculateFravalgteSavingsYearOne();
    $this->anlaegsinvestering = $this->calculateAnlaegsinvestering();
    $this->fravalgtAnlaegsinvestering = $this->calculateFravalgtAnlaegsinvestering();
    $this->nutidsvaerdiSetOver15AarKr = $this->calculateNutidsvaerdiSetOver15AarKr();
    $this->fravalgtNutidsvaerdiSetOver15AarKr = $this->calculateFravalgtNutidsvaerdiSetOver15AarKr();
    $this->genopretning = $this->calculateGenopretning();
    $this->modernisering = $this->calculateModernisering();
    $this->fravalgtGenopretning = $this->calculateFravalgtGenopretning();
    $this->fravalgtModernisering = $this->calculateModernisering();

    $this->cashFlow15 = $this->calculateCashFlow15();
    $this->cashFlow30 = $this->calculateCashFlow30();

    $this->internRenteInklFaellesomkostninger = $this->calculateInternRenteInklFaellesomkostninger();
  }

  private function calculateCashFlow15() {
    $result = array_fill(1, 15, 0);
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $cashflow = $tiltag->getCashFlow15();
      if(count($cashflow) == 15) {
        for($i = 1; $i <= 15; $i++) {
          $result[$i] += $cashflow[$i];
        }
      }
    }

    return $result;
  }

  private function calculateCashFlow30() {
    $result = array_fill(1, 30, 0);
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $cashflow = $tiltag->getCashFlow15();
      if(count($cashflow) == 30) {
        for($i = 1; $i <= 30; $i++) {
          $result[$i] += $cashflow[$i];
        }
      }
    }

    return $result;
  }

  private function calculateGenopretning() {
    $value = 0;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $value += $tiltag->getGenopretning();
    }
    return $value;
  }

  private function calculateModernisering() {
    $value = 0;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $value += $tiltag->getModernisering();
    }
    return $value;
  }

  private function calculateFravalgtGenopretning() {
    $value = 0;
    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $value += $tiltag->getGenopretning();
    }
    return $value;
  }

  private function calculateFravalgtModernisering() {
    $value = 0;
    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $value += $tiltag->getModernisering();
    }
    return $value;
  }

  private function calculateBesparelseVarmeGUF() {
    $value = 0;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $value += $tiltag->getVarmebesparelseGUF();
    }
    return $value;
  }

  private function calculateFravalgtBesparelseVarmeGUF() {
    $value = 0;
    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $value += $tiltag->getVarmebesparelseGUF();
    }
    return $value;
  }

  private function calculateBesparelseVarmeGAF() {
    $value = 0;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $value += $tiltag->getVarmebesparelseGAF();
    }
    return $value;
  }

  private function calculateFravalgtBesparelseVarmeGAF() {
    $value = 0;
    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $value += $tiltag->getVarmebesparelseGAF();
    }
    return $value;
  }

  private function calculateBesparelseCO2() {
    $value = 0;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $value += $tiltag->getSamletCo2besparelse();
    }
    return $value;
  }

  private function calculateFravalgtBesparelseCO2() {
    $value = 0;
    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $value += $tiltag->getSamletCo2besparelse();
    }
    return $value;
  }

  private function calculateBesparelseEl() {
    $value = 0;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $value += $tiltag->getElBesparelse();
    }
    return $value;
  }

  private function calculateBaselineCO2El() {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkEl();
    $elKgCo2MWh = !$forsyningsvaerk ? 0 : $forsyningsvaerk->getKgCo2MWh(2009);

    return $this->BaselineEl * $elKgCo2MWh / 1000;
  }

  private function calculateBaselineCO2Varme() {
    $forsyningsvaerk = $this->bygning->getForsyningsvaerkVarme();
    $varmeKgCo2MWh = !$forsyningsvaerk ? 0 : $forsyningsvaerk->getKgCo2MWh(2009);

    return ($this->BaselineVarmeGUF + $this->BaselineVarmeGAF) * $varmeKgCo2MWh / 1000;
  }

  private function calculateBaselineCO2Samlet() {
    return $this->BaselineCO2El + $this->BaselineCO2Varme;
  }

  private function calculateCO2BesparelseElProcent() {
    if ($this->BaselineCO2El != 0) {
      return ($this->BaselineCO2El - $this->co2BesparelseEl) / $this->BaselineCO2El * 100;
    }
    return null;
  }

  private function calculateCO2BesparelseVarmeProcent() {
    if ($this->BaselineCO2Varme != 0) {
      return ($this->BaselineCO2Varme - $this->co2BesparelseVarme) / $this->BaselineCO2Varme * 100;
    }
    return null;
  }

  private function calculateCO2BesparelseSamletProcent() {
    if ($this->BaselineCO2Samlet != 0) {
      return ($this->BaselineCO2Samlet - $this->besparelseCO2) / $this->BaselineCO2Samlet * 100;
    }
    return null;
  }

  private function calculateCo2BesparelseEl() {
    $year = $this->datering->format("Y");
    $vaerk = $this->getBygning()->getForsyningsvaerkEl();

    if($vaerk) {
      $ElKgCo2MWh = $this->getBygning()->getForsyningsvaerkEl()->getKgCo2MWh($year);
      return ($this->besparelseEl / 1000) * ($ElKgCo2MWh / 1000);
    } else {
      return 0;
    }
  }

  private function calculateCo2BesparelseVarme() {
    $year = $this->datering->format("Y");

    $vaerk = $this->getBygning()->getForsyningsvaerkVarme();
    if($vaerk) {
      $VarmeKgCo2MWh = $this->getBygning()->getForsyningsvaerkVarme()->getKgCo2MWh($year);
      return (($this->besparelseVarmeGAF + $this->besparelseVarmeGUF) / 1000) * ($VarmeKgCo2MWh / 1000);
    } else {
      return 0;
    }
  }

  private function calculateFravalgtBesparelseEl() {
    $value = 0;
    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $value += $tiltag->getSamletEnergibesparelse();
    }
    return $value;
  }

  private function calculateMtmFaellesomkostninger() {
    return 10000 + 10 * $this->bygning->getBruttoetageareal();
  }

  private function calculateImplementering() {
    $sum = 0;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $sum += $tiltag->getAnlaegsinvestering();
    }

    return $sum * $this->getProcentAfInvestering();
  }

  private function calculateFravalgtImplementering() {
    $sum = 0;
    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $sum += $tiltag->getAnlaegsinvestering();
    }

    $sum -= $this->genopretning;
    $sum -= $this->modernisering;

    return $sum * $this->getProcentAfInvestering();
  }

  private function calculateFaellesomkostninger() {
    return $this->mtmFaellesomkostninger + $this->implementering;
  }

  private function calculateSavingsYearOne() {
    $result = 0;

    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $result += $tiltag->getBesparelseAarEt();
    }

    return $result;
  }

  private function calculateFravalgteSavingsYearOne() {
    $result = 0;

    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $result += $tiltag->getBesparelseAarEt();
    }

    return $result;
  }

  protected function calculateAnlaegsinvestering() {
    $result = 0;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $result += $tiltag->getAnlaegsinvestering();
    }

    return $result;
  }

  protected function calculateFravalgtAnlaegsinvestering() {
    $result = 0;
    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $result += $tiltag->getAnlaegsinvestering();
    }

    return $result;
  }

  protected function calculateNutidsvaerdiSetOver15AarKr() {
    $result = 0;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $result += $tiltag->getNutidsvaerdiSetOver15AarKr();
    }

    return $result;
  }

  protected function calculateFravalgtNutidsvaerdiSetOver15AarKr() {
    $result = 0;
    foreach ($this->getFravalgteTiltag() as $tiltag) {
      $result += $tiltag->getNutidsvaerdiSetOver15AarKr();
    }

    return $result;
  }

  private function calculateInternRenteInklFaellesomkostninger() {
    return Excel::IRR($this->cashFlow15) * 100;
  }

  private function calculateCashFlow() {
    $numberOfYears = 30;
    $maxTiltagLevetid = $this->accumulate(function($tiltag, $value) {
      return $tiltag->getLevetid() > $value ? $tiltag->getLevetid() : $value;
    }, 0);

    $flow = array(
      'ydelse laan' => array_fill(0, $numberOfYears + 1, 0),
      'laan til faellesomkostninger' => array_fill(0, $numberOfYears + 1, 0),
      'ydelse laan inkl. faellesomkostninger' => array_fill(0, $numberOfYears + 1, 0),
      'besparelse' => array_fill(0, $numberOfYears + 1, 0),
      'cash flow' => array_fill(0, $numberOfYears + 1, 0),
      'akkumuleret' => array_fill(0, $numberOfYears + 1, 0),
    );

    $tilvalgteTiltag = $this->getTilvalgteTiltag();
    $rente = $this->getKalkulationsrente();
    $loebetid = $this->getLaanLoebetid();
    $samletAarligYdelseTilLaan = 0;

    foreach ($tilvalgteTiltag as $index => $tiltag) {
      $samletAarligYdelseTilLaan += Calculation::pmt($rente, $loebetid, $tiltag->getAnlaegsinvestering());
    }

    for ($year = 1; $year <= $numberOfYears; $year++) {
      $flow['ydelse laan'][$year] = ($year <= $loebetid) ? -$samletAarligYdelseTilLaan : NULL;
      $flow['laan til faellesomkostninger'][$year] = ($year <= $loebetid) ? -Calculation::pmt($rente, $loebetid, $this->faellesomkostninger) : NULL;
      $flow['ydelse laan inkl. faellesomkostninger'][$year] = $flow['ydelse laan'][$year] + $flow['laan til faellesomkostninger'][$year];
      $besparelse = $year > $maxTiltagLevetid ? NULL : $this->accumulate(function($tiltag, $value) use ($year) {
        return $value + $tiltag->calculateSavingsForYear($year);
      }, 0);

      $flow['besparelse'][$year] = $besparelse;
      $flow['cash flow'][$year] = -$flow['ydelse laan'][$year] + $flow['besparelse'][$year];
      $flow['akkumuleret'][$year] = $flow['akkumuleret'][$year - 1] + $flow['cash flow'][$year];
    }

    // Remove year 0.
    foreach ($flow as &$row) {
      unset($row[0]);
    }

    return $flow;
  }

  /**
   * Accumulate over all tilvalgte Tiltag in this Rapport.
   *
   * @param callable $accumulator
   *   The accumulator.
   * @param mixed $start
   *   The start value.
   *
   * @return mixed
   *   The accumulated value.
   */
  protected function accumulate(callable $accumulator, $start = 0) {
    $value = $start;
    foreach ($this->getTilvalgteTiltag() as $tiltag) {
      $value = $accumulator($tiltag, $value);
    }
    return $value;
  }

}

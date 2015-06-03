<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OrderBy;
use JMS\Serializer\Annotation as JMS;

/**
 * Rapport
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RapportRepository")
 */
class Rapport {
  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ManyToOne(targetEntity="Bygning", inversedBy="rapporter", fetch="EAGER")
   * @JoinColumn(name="bygning_id", referencedColumnName="id")
   **/
  private $bygning;


  /**
   * @OneToMany(targetEntity="Tiltag", mappedBy="rapport", cascade={"persist", "remove"})
   * @OrderBy({"title" = "ASC"})
   * @JMS\Type("Doctrine\Common\Collections\ArrayCollection<AppBundle\Entity\Tiltag>")
   */
  private $tiltag;

  /**
   * @var string
   *
   * @ORM\Column(name="version", type="string", length=255)
   */
  private $version;

  /**
   * @var \DateTime
   *
   * @ORM\Column(name="Datering", type="date")
   */
  private $datering;

  /**
   * @var integer
   *
   * @ORM\Column(name="BaselineEl", type="integer", nullable=true)
   */
  private $BaselineEl;

  /**
   * @var integer
   *
   * @ORM\Column(name="BaselineVarmeGUF", type="integer", nullable=true)
   */
  private $BaselineVarmeGUF;

  /**
   * @var integer
   *
   * @ORM\Column(name="BaselineVarmeGAF", type="integer", nullable=true)
   */
  private $BaselineVarmeGAF;

  /**
   * @var integer
   *
   * @ORM\Column(name="BaselineVand", type="integer", nullable=true)
   */
  private $BaselineVand;

  /**
   * @var integer
   *
   * @ORM\Column(name="BaselineStrafAfkoeling", type="integer", nullable=true)
   */
  private $BaselineStrafAfkoeling;

  /**
   * @var integer
   *
   * @ORM\Column(name="SamtidighedsFaktor", type="integer", nullable=true)
   */
  private $SamtidighedsFaktor;

  /**
   * @var integer
   *
   * @ORM\Column(name="Energiscreening", type="integer", nullable=true)
   */
  private $Energiscreening;


  /**
   * Constructor
   */
  public function __construct() {
    $this->tiltag = new \Doctrine\Common\Collections\ArrayCollection();
    $this->datering = new \DateTime();
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
   * Set BaselineEl
   *
   * @param integer $baselineEl
   * @return Rapport
   */
  public function setBaselineEl($baselineEl)
  {
    $this->BaselineEl = $baselineEl;

    return $this;
  }

  /**
   * Get BaselineEl
   *
   * @return integer
   */
  public function getBaselineEl()
  {
    return $this->BaselineEl;
  }

  /**
   * Set BaselineVarmeGUF
   *
   * @param integer $baselineVarmeGUF
   * @return Rapport
   */
  public function setBaselineVarmeGUF($baselineVarmeGUF)
  {
    $this->BaselineVarmeGUF = $baselineVarmeGUF;

    return $this;
  }

  /**
   * Get BaselineVarmeGUF
   *
   * @return integer
   */
  public function getBaselineVarmeGUF()
  {
    return $this->BaselineVarmeGUF;
  }

  /**
   * Set BaselineVarmeGAF
   *
   * @param integer $baselineVarmeGAF
   * @return Rapport
   */
  public function setBaselineVarmeGAF($baselineVarmeGAF)
  {
    $this->BaselineVarmeGAF = $baselineVarmeGAF;

    return $this;
  }

  /**
   * Get BaselineVarmeGAF
   *
   * @return integer
   */
  public function getBaselineVarmeGAF()
  {
    return $this->BaselineVarmeGAF;
  }

  /**
   * Set BaselineVand
   *
   * @param integer $baselineVand
   * @return Rapport
   */
  public function setBaselineVand($baselineVand)
  {
    $this->BaselineVand = $baselineVand;

    return $this;
  }

  /**
   * Get BaselineVand
   *
   * @return integer
   */
  public function getBaselineVand()
  {
    return $this->BaselineVand;
  }

  /**
   * Set BaselineStrafAfkoeling
   *
   * @param integer $baselineStrafAfkoeling
   * @return Rapport
   */
  public function setBaselineStrafAfkoeling($baselineStrafAfkoeling)
  {
    $this->BaselineStrafAfkoeling = $baselineStrafAfkoeling;

    return $this;
  }

  /**
   * Get BaselineStrafAfkoeling
   *
   * @return integer
   */
  public function getBaselineStrafAfkoeling()
  {
    return $this->BaselineStrafAfkoeling;
  }

  /**
   * Set SamtidighedsFaktor
   *
   * @param integer $samtidighedsFaktor
   * @return Rapport
   */
  public function setSamtidighedsFaktor($samtidighedsFaktor)
  {
    $this->SamtidighedsFaktor = $samtidighedsFaktor;

    return $this;
  }

  /**
   * Get SamtidighedsFaktor
   *
   * @return integer
   */
  public function getSamtidighedsFaktor()
  {
    return $this->SamtidighedsFaktor;
  }

  /**
   * Set Energiscreening
   *
   * @param integer $energiscreening
   * @return Rapport
   */
  public function setEnergiscreening($energiscreening)
  {
    $this->Energiscreening = $energiscreening;

    return $this;
  }

  /**
   * Get Energiscreening
   *
   * @return integer
   */
  public function getEnergiscreening()
  {
    return $this->Energiscreening;
  }

  /**
   * Get Energiscreening
   *
   * @return integer
   */
  public function getTotalVarme()
  {
    return $this->getBaselineVarmeGAF() + $this->getBaselineVarmeGUF();
  }

  public function getKalkulationsrente()
  {
    // Worksheets("1.Tiltagslisterådgiver").Range("ai23").Value;
    // @FIXME: Where should we get/store this value?
    return 0.0292;
  }

  public function getInflationsfaktor()
  {
    // Worksheets("1.Tiltagslisterådgiver").Range("ai26");
    // @FIXME: Where should we get/store this value?
    return 13.8639998427607;
  }

  public function getInflation()
  {
    // Worksheets("1.Tiltagslisterådgiver").Range("ak23");
    // @FIXME: Where should we get/store this value?
    return 0.019;
  }

  public function getLobetid()
  {
    // Worksheets("1.Tiltagslisterådgiver").Range("an23");
    // @FIXME: Where should we get/store this value?
    return 15;
  }

  public function getElfaktor()
  {
    // Worksheets("1.TiltagslisteRådgiver").Range("ah25");
    // @FIXME: Where should we get/store this value?
    return 23.5852836416293;
  }

  public function getVarmefaktor()
  {
    // Worksheets("1.TiltagslisteRådgiver").Range("ah24");
    // @FIXME: Where should we get/store this value?
    return 5.66155209590081;
  }

  public function getVandfaktor()
  {
    // Worksheets("1.TiltagslisteRådgiver").Range("ai27");
    // @FIXME: Where should we get/store this value?
    return 566.621673573629;
  }

  public function getVarmeKrKWh()
  {
    // Worksheets("1.TiltagslisteRådgiver").Range("ai6");
    // @FIXME: Where should we get/store this value?
    return 0.491;
  }

  public function getElKrKWh()
  {
    // Worksheets("1.TiltagslisteRådgiver").Range("ai7");
    // @FIXME: Where should we get/store this value?
    return 1.609478;
  }

  public function isStandardforsyning() {
    // INDIRECT(\"'2.Forsyning'!$H$3\")=1
    // =HVIS(
		// 	OG(
		// 		A15="Hovedforsyning El";
		// 		J15="El";
		// 		I15=1;
		// 		H15=1;
		// 		A16="Fjernvarme";
		// 		J16="Varme";
		// 		I16=1;
		// 		H16=1
		// 	);
		// 	1;
		// 	"ikke standardforsyning"
		// )

    // A15: text, fx "Hovedforsyning El"
    // J15: 1. Interne Produktion, PRISGRUNDLAG 1
    // I15: 1. Interne Produktion, Effektivitet enhed/kWh 1
    // H15: 1. Interne Produktion, %-Fordeling 1
    // A16: text, fx "Fjernvarme"
    // J16: 1. Interne Produktion, PRISGRUNDLAG 1
    // I16: 1. Interne Produktion, Effektivitet enhed/kWh 1
    // H16: 1. Interne Produktion, %-Fordeling 1

    // @FIXME: Where should we get/store this value?
    return true;
  }

}

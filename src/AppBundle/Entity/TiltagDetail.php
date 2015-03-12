<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\InheritanceType;

use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * TiltagDetail
 *
 * @ORM\Table()
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({ "pumpe" = "PumpeTiltagDetail", "special" = "SpecialTiltagDetail" })
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TiltagDetailRepository")
 */
abstract class TiltagDetail {
  use TimestampableEntity;

  /**
   * @var integer
   *
   * @ORM\Column(name="id", type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * Get id
   *
   * @return integer
   */
  final public function getId() {
    return $this->id;
  }

  /**
   * @var string
   *
   * @ORM\Column(name="Title", type="string", length=255, nullable=true)
   */
  private $title;

  public function setTitle($title) {
    $this->title = $title;
    return $this;
  }

  public function getTitle() {
    return $this->title;
  }

  /**
   * @ManyToOne(targetEntity="Tiltag", inversedBy="details")
   * @JoinColumn(name="tiltag_id", referencedColumnName="id")
   **/
  private $tiltag;

  /**
   * Set tiltag
   *
   * @param \AppBundle\Entity\tiltag $tiltag
   * @return Rapport
   */
  public function setTiltag(Tiltag $tiltag = NULL) {
    $this->tiltag = $tiltag;

    return $this;
  }

  /**
   * Get tiltag
   *
   * @return \AppBundle\Entity\tiltag
   */
  public function getTiltag() {
    return $this->tiltag;
  }

    /**
   * @var boolean
   *
   * @ORM\Column(name="tilvalgt", type="boolean")
   */
  private $tilvalgt = false;

  /**
   * Set tilvalgt
   *
   * @param boolean $tilvalgt
   * @return PumpeDetail
   */
  public function setTilvalgt($tilvalgt) {
    $this->tilvalgt = $tilvalgt;

    return $this;
  }

  /**
   * Get tilvalgt
   *
   * @return boolean
   */
  public function getTilvalgt() {
    return $this->tilvalgt;
  }

  public function handleUploads($manager) {}
}
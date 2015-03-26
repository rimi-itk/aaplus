<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BelysningTiltag.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\BelysningTiltagRepository")
 */
class BelysningTiltag extends Tiltag {

  /**
   * Constructor
   */
  public function __construct() {
    $this->pumpedetails = new \Doctrine\Common\Collections\ArrayCollection();

    // @Todo: Find af way to use the translations system or move this to some place else....
    $this->setTitle('Belysningstiltag');
  }
}

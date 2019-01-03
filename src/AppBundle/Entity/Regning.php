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
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

/**
 * Regning.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\RegningRepository")
 */
class Regning
{
    use TimestampableEntity;

    /**
     * @var Tiltag
     *
     * @ManyToOne(targetEntity="Tiltag", inversedBy="regning")
     * @JoinColumn(name="tiltag_id", referencedColumnName="id")
     * @JMS\Type("AppBundle\Entity\Tiltag")
     **/
    protected $tiltag;

    /**
     * @var Leverandoer
     *
     * @ManyToOne(targetEntity="Leverandoer", inversedBy="regninger")
     * @JoinColumn(name="leverandoer_id", referencedColumnName="id")
     * @JMS\Type("AppBundle\Entity\Leverandoer")
     **/
    protected $leverandoer;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="nummer", type="integer", nullable=true)
     */
    private $nummer;

    /**
     * @var float
     *
     * @ORM\Column(name="faktureret", type="float", nullable=true)
     */
    private $faktureret;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startdato", type="date", nullable=true)
     */
    private $startdato;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="slutdato", type="date", nullable=true)
     */
    private $slutdato;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get nummer.
     *
     * @return int
     */
    public function getNummer()
    {
        return $this->nummer;
    }

    /**
     * Set nummer.
     *
     * @param int $nummer
     *
     * @return Regning
     */
    public function setNummer($nummer)
    {
        $this->nummer = $nummer;

        return $this;
    }

    /**
     * Get faktureret.
     *
     * @return float
     */
    public function getFaktureret()
    {
        return $this->faktureret;
    }

    /**
     * Set faktureret.
     *
     * @param float $faktureret
     *
     * @return Regning
     */
    public function setFaktureret($faktureret)
    {
        $this->faktureret = $faktureret;

        return $this;
    }

    /**
     * Get startdato.
     *
     * @return \DateTime
     */
    public function getStartdato()
    {
        return $this->startdato;
    }

    /**
     * Set startdato.
     *
     * @param \DateTime $startdato
     *
     * @return Regning
     */
    public function setStartdato($startdato)
    {
        $this->startdato = $startdato;

        return $this;
    }

    /**
     * Get slutdato.
     *
     * @return \DateTime
     */
    public function getSlutdato()
    {
        return $this->slutdato;
    }

    /**
     * Set slutdato.
     *
     * @param \DateTime $slutdato
     *
     * @return Regning
     */
    public function setSlutdato($slutdato)
    {
        $this->slutdato = $slutdato;

        return $this;
    }

    /**
     * Get tiltag.
     *
     * @return \AppBundle\Entity\Tiltag
     */
    public function getTiltag()
    {
        return $this->tiltag;
    }

    /**
     * Set tiltag.
     *
     * @param \AppBundle\Entity\Tiltag $tiltag
     *
     * @return Regning
     */
    public function setTiltag(\AppBundle\Entity\Tiltag $tiltag = null)
    {
        $this->tiltag = $tiltag;

        return $this;
    }

    /**
     * Get leverandoer.
     *
     * @return \AppBundle\Entity\Leverandoer
     */
    public function getLeverandoer()
    {
        return $this->leverandoer;
    }

    /**
     * Set leverandoer.
     *
     * @param \AppBundle\Entity\Leverandoer $leverandoer
     *
     * @return Regning
     */
    public function setLeverandoer(\AppBundle\Entity\Leverandoer $leverandoer = null)
    {
        $this->leverandoer = $leverandoer;

        return $this;
    }
}

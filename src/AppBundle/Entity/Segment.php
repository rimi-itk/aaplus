<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * Segment.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\SegmentRepository")
 */
class Segment
{
    /**
     * @OneToMany(targetEntity="Bygning", mappedBy="segment")
     **/
    protected $bygninger;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="segmenter", fetch="EAGER")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     **/
    protected $segmentAnsvarlig;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Navn", type="string", length=255)
     */
    private $navn;

    /**
     * @var string
     *
     * @ORM\Column(name="Forkortelse", type="string", length=5)
     */
    private $forkortelse;

    /**
     * @var string
     *
     * @ORM\Column(name="Magistrat", type="string", length=255)
     */
    private $magistrat;

    public function __construct()
    {
        $this->bygninger = new ArrayCollection();
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getNavn().' / '.$this->getForkortelse();
    }

    /**
     * Get navn.
     *
     * @return string
     */
    public function getNavn()
    {
        return $this->navn;
    }

    /**
     * Set navn.
     *
     * @param string $navn
     *
     * @return Segment
     */
    public function setNavn($navn)
    {
        $this->navn = $navn;

        return $this;
    }

    /**
     * Get forkortelse.
     *
     * @return string
     */
    public function getForkortelse()
    {
        return $this->forkortelse;
    }

    /**
     * Set forkortelse.
     *
     * @param string $forkortelse
     *
     * @return Segment
     */
    public function setForkortelse($forkortelse)
    {
        $this->forkortelse = $forkortelse;

        return $this;
    }

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
     * Get magistrat.
     *
     * @return string
     */
    public function getMagistrat()
    {
        return $this->magistrat;
    }

    /**
     * Set magistrat.
     *
     * @param string $magistrat
     *
     * @return Segment
     */
    public function setMagistrat($magistrat)
    {
        $this->magistrat = $magistrat;

        return $this;
    }

    /**
     * Add bygninger.
     *
     * @param \AppBundle\Entity\Bygning $bygninger
     *
     * @return Segment
     */
    public function addBygninger(\AppBundle\Entity\Bygning $bygning)
    {
        $this->bygninger[] = $bygning;

        return $this;
    }

    /**
     * Remove bygninger.
     *
     * @param \AppBundle\Entity\Bygning $bygninger
     */
    public function removeBygninger(\AppBundle\Entity\Bygning $bygning)
    {
        $this->bygninger->removeElement($bygning);
    }

    /**
     * Get bygninger.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBygninger()
    {
        return $this->bygninger;
    }

    /**
     * Set segmentAnsvarlig.
     *
     * @param \AppBundle\Entity\User $segmentAnsvarlig
     *
     * @return Segment
     */
    public function removeSegmentAnsvarlig()
    {
        $this->segmentAnsvarlig = null;

        return $this;
    }

    /**
     * Get segmentAnsvarlig.
     *
     * @return \AppBundle\Entity\User
     */
    public function getSegmentAnsvarlig()
    {
        return $this->segmentAnsvarlig;
    }

    /**
     * Set segmentAnsvarlig.
     *
     * @param \AppBundle\Entity\User $segmentAnsvarlig
     *
     * @return Segment
     */
    public function setSegmentAnsvarlig(\AppBundle\Entity\User $segmentAnsvarlig = null)
    {
        $this->segmentAnsvarlig = $segmentAnsvarlig;

        return $this;
    }
}

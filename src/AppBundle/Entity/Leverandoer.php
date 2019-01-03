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
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use JMS\Serializer\Annotation as JMS;

/**
 * Leverandoer.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Leverandoer
{
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var ArrayCollection
     *
     * @OneToMany(targetEntity="Regning", mappedBy="leverandoer", cascade={"persist", "remove"})
     * @OrderBy({"createdAt" = "ASC"})
     * @JMS\Type("Doctrine\Common\Collections\ArrayCollection<AppBundle\Entity\Regning>")
     */
    private $regninger;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->regninger = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Leverandoer
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Add regning.
     *
     * @param \AppBundle\Entity\Regning $regning
     *
     * @return Leverandoer
     */
    public function addRegning(\AppBundle\Entity\Regning $regning)
    {
        $this->regninger[] = $regning;

        return $this;
    }

    /**
     * Remove regning.
     *
     * @param \AppBundle\Entity\Regning $regning
     */
    public function removeRegning(\AppBundle\Entity\Regning $regning)
    {
        $this->regninger->removeElement($regning);
    }

    /**
     * Get regning.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegning()
    {
        return $this->regninger;
    }
}

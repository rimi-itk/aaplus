<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity\TekniskIsoleringTiltagDetail;

use Doctrine\ORM\Mapping as ORM;

/**
 * NyttiggjortVarme.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TekniskIsoleringTiltagDetail\NyttiggjortVarmeRepository")
 */
class NyttiggjortVarme
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
     * @var float
     *
     * @ORM\Column(name="faktor", type="float")
     */
    private $faktor;

    /**
     * @var string
     *
     * @ORM\Column(name="titel", type="string", length=255)
     */
    private $titel;

    /**
     * Get Name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFaktor().' - '.$this->getTitel();
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
     * Set faktor.
     *
     * @param float $faktor
     *
     * @return NyttiggjortVarme
     */
    public function setFaktor($faktor)
    {
        $this->faktor = $faktor;

        return $this;
    }

    /**
     * Get faktor.
     *
     * @return float
     */
    public function getFaktor()
    {
        return $this->faktor;
    }

    /**
     * Set titel.
     *
     * @param string $titel
     *
     * @return NyttiggjortVarme
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;

        return $this;
    }

    /**
     * Get titel.
     *
     * @return string
     */
    public function getTitel()
    {
        return $this->titel;
    }
}

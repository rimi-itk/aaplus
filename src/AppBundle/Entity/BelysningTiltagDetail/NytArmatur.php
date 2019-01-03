<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity\BelysningTiltagDetail;

use Doctrine\ORM\Mapping as ORM;

/**
 * BelysningTiltagDetail:NytArmatur.
 *
 * @ORM\Table(name="BelysningTiltagDetail_NytArmatur")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\BelysningTiltagDetail\NytArmaturRepository")
 */
class NytArmatur
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
     * @ORM\Column(name="arbejde_omfang", type="string", length=255)
     */
    private $arbejdeOmfang;

    /**
     * @var int
     *
     * @ORM\Column(name="nyLyskildeAntal", type="integer", nullable=true)
     */
    private $nyLyskildeAntal;

    /**
     * @var int
     *
     * @ORM\Column(name="wattage", type="integer", nullable=true)
     */
    private $wattage;

    /**
     * @var int
     *
     * @ORM\Column(name="nyeForkoblingerAntal", type="integer", nullable=true)
     */
    private $nyeForkoblingerAntal;

    /**
     * @var float
     *
     * @ORM\Column(name="pris", type="decimal", scale=4, nullable=true)
     */
    private $pris;

    /**
     * @var string
     *
     * @ORM\Column(name="noter", type="string", length=255, nullable=true)
     */
    private $noter;

    public function __toString()
    {
        return $this->arbejdeOmfang.' - '.number_format($this->pris, 2, ',', '.').' kr.';
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
     * Set arbejdeOmfang.
     *
     * @param string $arbejdeOmfang
     *
     * @return BelysningTiltagDetail:NytArmatur
     */
    public function setArbejdeOmfang($arbejdeOmfang)
    {
        $this->arbejdeOmfang = $arbejdeOmfang;

        return $this;
    }

    /**
     * Get arbejdeOmfang.
     *
     * @return string
     */
    public function getArbejdeOmfang()
    {
        return $this->arbejdeOmfang;
    }

    /**
     * Set nyLyskildeAntal.
     *
     * @param int $nyLyskildeAntal
     *
     * @return BelysningTiltagDetail:NytArmatur
     */
    public function setNyLyskildeAntal($nyLyskildeAntal)
    {
        $this->nyLyskildeAntal = $nyLyskildeAntal;

        return $this;
    }

    /**
     * Get nyLyskildeAntal.
     *
     * @return int
     */
    public function getNyLyskildeAntal()
    {
        return $this->nyLyskildeAntal;
    }

    /**
     * Set wattage.
     *
     * @param int $wattage
     *
     * @return BelysningTiltagDetail:NytArmatur
     */
    public function setWattage($wattage)
    {
        $this->wattage = $wattage;

        return $this;
    }

    /**
     * Get wattage.
     *
     * @return int
     */
    public function getWattage()
    {
        return $this->wattage;
    }

    /**
     * Set nyeForkoblingerAntal.
     *
     * @param int $nyeForkoblingerAntal
     *
     * @return BelysningTiltagDetail:NytArmatur
     */
    public function setNyeForkoblingerAntal($nyeForkoblingerAntal)
    {
        $this->nyeForkoblingerAntal = $nyeForkoblingerAntal;

        return $this;
    }

    /**
     * Get nyeForkoblingerAntal.
     *
     * @return int
     */
    public function getNyeForkoblingerAntal()
    {
        return $this->nyeForkoblingerAntal;
    }

    /**
     * Set pris.
     *
     * @param float $pris
     *
     * @return BelysningTiltagDetail:NytArmatur
     */
    public function setPris($pris)
    {
        $this->pris = $pris;

        return $this;
    }

    /**
     * Get pris.
     *
     * @return float
     */
    public function getPris()
    {
        return $this->pris;
    }

    /**
     * Set noter.
     *
     * @param string $noter
     *
     * @return BelysningTiltagDetail:NytArmatur
     */
    public function setNoter($noter)
    {
        $this->noter = $noter;

        return $this;
    }

    /**
     * Get noter.
     *
     * @return string
     */
    public function getNoter()
    {
        return $this->noter;
    }
}

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
 * Styring.
 *
 * @ORM\Table(name="BelysningTiltagDetail_NyStyring")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\BelysningTiltagDetail\NyStyringRepository")
 */
class NyStyring
{
    /**
     * @var float
     *
     * @ORM\Column(name="pris", type="decimal", scale=4, nullable=true)
     */
    protected $pris;
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
     * @ORM\Column(name="titel", type="string", length=255)
     */
    private $titel;

    /**
     * @var string
     *
     * @ORM\Column(name="noter", type="string", length=255, nullable=true)
     */
    private $noter;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deactivated_at", type="datetime", nullable=true)
     */
    private $deactivatedAt;

    public function __toString()
    {
        $result = $this->titel;

        if ($this->pris) {
            $result .= ' - '.number_format($this->pris, 2, ',', '.').' kr';
        }

        return $result;
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
     * Set titel.
     *
     * @param string $titel
     *
     * @return Styring
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

    /**
     * @return float
     */
    public function getPris()
    {
        return $this->pris;
    }

    /**
     * @param float $pris
     */
    public function setPris($pris)
    {
        $this->pris = $pris;
    }

    /**
     * @return string
     */
    public function getNoter()
    {
        return $this->noter;
    }

    /**
     * @param string $noter
     */
    public function setNoter($noter)
    {
        $this->noter = $noter;
    }
}

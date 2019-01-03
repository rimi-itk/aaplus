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
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * BelysningTiltagDetail\Lyskilde.
 *
 * Belysningstype
 *
 * @ORM\Table(name="BelysningTiltagDetail_Lyskilde")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\BelysningTiltagDetail\LyskildeRepository")
 */
class Lyskilde
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="navn", type="string", length=255)
     */
    protected $navn;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="forkobling", type="string", length=255)
     */
    protected $forkobling;

    /**
     * @var float
     *
     * @ORM\Column(name="udgift", type="decimal", scale=2)
     */
    protected $udgift;

    /**
     * @var int
     *
     * @ORM\Column(name="levetid", type="integer")
     */
    protected $levetid;

    public function __toString()
    {
        return $this->navn;
    }

    /**
     * Get id.
     *
     * @return int
     */
    final public function getId()
    {
        return $this->id;
    }

    public function setNavn($navn)
    {
        $this->navn = $navn;

        return $this;
    }

    public function getNavn()
    {
        return $this->navn;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setForkobling($forkobling)
    {
        $this->forkobling = $forkobling;

        return $this;
    }

    public function getForkobling()
    {
        return $this->forkobling;
    }

    public function setUdgift($udgift)
    {
        $this->udgift = $udgift;

        return $this;
    }

    public function getUdgift()
    {
        return $this->udgift;
    }

    public function setLevetid($levetid)
    {
        $this->levetid = $levetid;

        return $this;
    }

    public function getLevetid()
    {
        return $this->levetid;
    }
}

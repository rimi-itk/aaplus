<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity\Energiforsyning;

use AppBundle\DBAL\Types\Energiforsyning\InternProduktion\PrisgrundlagType;
use AppBundle\Entity\Energiforsyning;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * InternProduktion.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EnergiforsyningRepository")
 */
class InternProduktion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="AppBundle\Entity\Energiforsyning", inversedBy="internProduktioner", fetch="EAGER")
     * @JoinColumn(name="energiforsyning_id", referencedColumnName="id")
     **/
    protected $energiforsyning;

    /**
     * @var string
     *
     * @ORM\Column(name="navn", type="string", length=255)
     */
    protected $navn;

    /**
     * @var float
     *
     * @ORM\Column(name="fordeling", type="decimal", scale=4)
     */
    protected $fordeling;

    /**
     * @var float
     *
     * @ORM\Column(name="effektivitet", type="decimal", scale=4)
     */
    protected $effektivitet;

    /**
     * @var string
     *
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Energiforsyning\InternProduktion\PrisgrundlagType")
     * @ORM\Column(name="prisgrundlag", type="PrisgrundlagType", length=255)
     */
    protected $prisgrundlag;

    public function __toString()
    {
        return Prisgrundlag::getReadableValue($this->prisgrundlag);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEnergiforsyning($energiforsyning)
    {
        $this->energiforsyning = $energiforsyning;

        return $this;
    }

    public function getEnergiforsyning()
    {
        return $this->energiforsyning;
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

    public function setFordeling($fordeling)
    {
        $this->fordeling = $fordeling;

        return $this;
    }

    public function getFordeling()
    {
        return $this->fordeling;
    }

    public function setEffektivitet($effektivitet)
    {
        $this->effektivitet = $effektivitet;

        return $this;
    }

    public function getEffektivitet()
    {
        return $this->effektivitet;
    }

    public function setPrisgrundlag($prisgrundlag)
    {
        $this->prisgrundlag = $prisgrundlag;

        return $this;
    }

    public function getPrisgrundlag()
    {
        return $this->prisgrundlag;
    }
}

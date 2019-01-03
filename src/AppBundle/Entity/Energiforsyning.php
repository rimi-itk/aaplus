<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use AppBundle\Annotations\Calculated;
use AppBundle\DBAL\Types\Energiforsyning\InternProduktion\PrisgrundlagType;
use AppBundle\DBAL\Types\Energiforsyning\NavnType;
use AppBundle\Entity\Energiforsyning\InternProduktion;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;
use JMS\Serializer\Annotation as JMS;

/**
 * Energiforsyning.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\EnergiforsyningRepository")
 */
class Energiforsyning
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
     * @ManyToOne(targetEntity="Rapport", inversedBy="energiforsyninger", fetch="EAGER")
     * @JoinColumn(name="rapport_id", referencedColumnName="id")
     **/
    protected $rapport;

    /**
     * @var string
     *
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\Energiforsyning\NavnType")
     * @ORM\Column(name="navn", type="NavnType", nullable=true)
     */
    protected $navn;

    /**
     * @var string
     *
     * @ORM\Column(name="beskrivelse", type="text")
     */
    protected $beskrivelse;

    /**
     * @var float
     *
     * @ORM\Column(name="prisfaktor", type="decimal", scale=4, nullable=true)
     */
    protected $prisfaktor;

    /**
     * @OneToMany(targetEntity="AppBundle\Entity\Energiforsyning\InternProduktion", mappedBy="energiforsyning", cascade={"persist", "remove"})
     * @OrderBy({"id" = "ASC"})
     * @JMS\Type("Doctrine\Common\Collections\ArrayCollection<AppBundle\Entity\Energiforsyning\InternProduktion>")
     */
    protected $internProduktioner;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="samletVarmeeffektivitet", type="float", nullable=true)
     */
    protected $samletVarmeeffektivitet;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="samletEleffektivitet", type="float", nullable=true)
     */
    protected $samletEleffektivitet;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->internProduktioner = new ArrayCollection();
    }

    public function __toString()
    {
        return NavnType::getReadableValue($this->navn);
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rapport.
     *
     * @param \AppBundle\Entity\Rapport $rapport
     *
     * @return Tiltag
     */
    public function setRapport(Rapport $rapport = null)
    {
        $this->rapport = $rapport;

        return $this;
    }

    public function getRapport()
    {
        return $this->rapport;
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

    public function setBeskrivelse($beskrivelse)
    {
        $this->beskrivelse = $beskrivelse;

        return $this;
    }

    public function getBeskrivelse()
    {
        return $this->beskrivelse;
    }

    public function getForsyningsvaerk()
    {
        if (!$this->getRapport() || !$this->getRapport()->getBygning()) {
            return null;
        }

        switch ($this->getNavn()) {
      case NavnType::FJERNVARME:
        return $this->getRapport()->getBygning()->getForsyningsvaerkVarme();
      case NavnType::OLIEFYR:
        return $this->getRapport()->getOlie();
      case NavnType::TRAEPILLEFYR:
        return $this->getRapport()->getTraepillefyr();
      case NavnType::HOVEDFORSYNING_EL:
      case NavnType::VARMEPUMPE:
        return $this->getRapport()->getBygning()->getForsyningsvaerkEl();
    }

        return null;
    }

    public function getEnhedspris()
    {
        $forsyningsvaerk = $this->getForsyningsvaerk();

        return $forsyningsvaerk ? $forsyningsvaerk->getKrKWh($this->getRapport()->getDatering()->format('Y')) : null;
    }

    public function setPrisfaktor($prisfaktor)
    {
        $this->prisfaktor = $prisfaktor;

        return $this;
    }

    public function getPrisfaktor()
    {
        return $this->prisfaktor ? $this->prisfaktor : 1;
    }

    public function getNyEnhedspris()
    {
        return $this->getEnhedspris() * $this->getPrisfaktor();
    }

    public function setInternProduktioner($internProduktioner)
    {
        $this->internProduktioner = $internProduktioner;

        return $this;
    }

    public function addInternProduktion(InternProduktion $internProduktion)
    {
        $this->internProduktioner[] = $internProduktion;

        $internProduktion->setEnergiforsyning($this);
        $this->calculate();

        return $this;
    }

    public function removeInternProduktion(InternProduktion $internProduktion)
    {
        $this->internProduktioner->removeElement($internProduktion);
        $this->calculate();
    }

    public function getInternProduktioner()
    {
        return $this->internProduktioner;
    }

    public function getSamletVarmeeffektivitet()
    {
        return $this->samletVarmeeffektivitet;
    }

    public function getSamletEleffektivitet()
    {
        return $this->samletEleffektivitet;
    }

    public function calculate()
    {
        $this->samletVarmeeffektivitet = $this->calculateSamletVarmeeffektivitet();
        $this->samletEleffektivitet = $this->calculateSamletEleffektivitet();
    }

    /*
     * Additional setter and getter to make automatic English singularization behave.
     * Symfony can thus get from internProduktions to internProduktion.
     */
    public function setInternProduktions($internProduktioner)
    {
        return $this->setInternProduktioner($internProduktioner);
    }

    public function getInternProduktions()
    {
        return $this->getInternProduktioner();
    }

    private function calculateSamletVarmeeffektivitet()
    {
        return array_reduce($this->internProduktioner->filter(function ($item) {
            return PrisgrundlagType::VARME === $item->getPrisgrundlag();
        })->toArray(), function ($carry, $item) {
            return $carry + (1 + (1 - $item->getEffektivitet())) * $item->getFordeling();
        }, 0);
    }

    private function calculateSamletEleffektivitet()
    {
        return array_reduce($this->internProduktioner->filter(function ($item) {
            return PrisgrundlagType::EL === $item->getPrisgrundlag();
        })->toArray(), function ($carry, $item) {
            return $carry + (1 + (1 - $item->getEffektivitet())) * $item->getFordeling();
        }, 0);
    }
}

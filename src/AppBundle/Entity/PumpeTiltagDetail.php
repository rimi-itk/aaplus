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
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

/**
 * PumpeTiltagDetail.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PumpeDetailRepository")
 */
class PumpeTiltagDetail extends TiltagDetail
{
    /**
     * @var string
     *
     * @ORM\Column(name="PumpeID", type="string", length=50)
     */
    protected $pumpeID;

    /**
     * @var string
     *
     * @ORM\Column(name="Forsyningsomraade", type="string", length=255)
     */
    protected $forsyningsomraade;

    /**
     * @var string
     *
     * @ORM\Column(name="Placering", type="string", length=255)
     */
    protected $placering;

    /**
     * @ManyToOne(targetEntity="PumpeTiltagDetailApplikation")
     * @JoinColumn(name="applikation_id", referencedColumnName="id")
     **/
    protected $applikation;

    /**
     * @var bool
     *
     * @ORM\Column(name="Isoleringskappe", type="boolean")
     */
    protected $isoleringskappe = false;

    /**
     * @var float
     *
     * @deprecated
     *
     * @ORM\Column(name="bFaktor", type="decimal", scale=4)
     */
    protected $bFaktor;

    /**
     * @var NyttiggjortVarme
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TekniskIsoleringTiltagDetail\NyttiggjortVarme", fetch="EAGER")
     * @ORM\JoinColumn(name="nyttiggjortvarme_id", referencedColumnName="id")
     */
    protected $nyttiggjortVarme;

    /**
     * @var string
     *
     * @ORM\Column(name="Noter", type="text", nullable=true)
     */
    protected $noter;

    /**
     * @var int
     *
     * @ORM\Column(name="EksisterendeDrifttid", type="integer")
     */
    protected $eksisterendeDrifttid;

    /**
     * @var int
     *
     * @ORM\Column(name="NyDrifttid", type="integer")
     */
    protected $nyDrifttid;

    /**
     * @var float
     *
     * @ORM\Column(name="Prisfaktor", type="decimal", scale=4)
     */
    protected $prisfaktor;

    /**
     * @ManyToOne(targetEntity="Pumpe")
     * @JoinColumn(name="pumpe_id", referencedColumnName="id")
     **/
    protected $pumpe;

    /**
     * @var float
     *
     * @ORM\Column(name="overskrevetPris", type="decimal", scale=4)
     */
    protected $overskrevetPris;

    /**
     * @var string
     *
     * @ORM\Column(name="varmetabIftAekvivalentRoerstoerrelse", type="string", length=10)
     */
    protected $varmetabIftAekvivalentRoerstoerrelse;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="pristillaeg", type="float")
     */
    protected $pristillaeg;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="samletInvesteringInklPristillaeg", type="float")
     */
    protected $samletInvesteringInklPristillaeg;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="elforbrugVedNyeDriftstidKWhAar", type="float")
     */
    protected $elforbrugVedNyeDriftstidKWhAar;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="elbespKWhAar", type="float")
     */
    protected $elbespKWhAar;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="varmebespIsokappeKWh", type="float")
     */
    protected $varmebespIsokappeKWh;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="simpelTilbagebetalingstidAar", type="float")
     */
    protected $simpelTilbagebetalingstidAar;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="nutidsvaerdiSetOver15AarKr", type="float")
     */
    protected $nutidsvaerdiSetOver15AarKr;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="kwhBesparelseElFraVaerket", type="float")
     */
    protected $kwhBesparelseElFraVaerket;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="kwhBesparelseVarmeFraVaerket", type="float")
     */
    protected $kwhBesparelseVarmeFraVaerket;

    protected $propertiesRequiredForCalculation = [
        'applikation',
        'eksisterendeDrifttid',
        'forsyningsomraade',
        'nyDrifttid',
        'nyttiggjortVarme',
        'placering',
        'prisfaktor',
        'pumpe',
        'pumpeID',
    ];

    /**
     * Get pumpeID.
     *
     * @return string
     */
    public function getPumpeID()
    {
        return $this->pumpeID;
    }

    /**
     * Set pumpeID.
     *
     * @param string $pumpeID
     *
     * @return PumpeDetail
     */
    public function setPumpeID($pumpeID)
    {
        $this->pumpeID = $pumpeID;

        return $this;
    }

    /**
     * Get forsyningsomraade.
     *
     * @return string
     */
    public function getForsyningsomraade()
    {
        return $this->forsyningsomraade;
    }

    /**
     * Set forsyningsomraade.
     *
     * @param string $forsyningsomraade
     *
     * @return PumpeDetail
     */
    public function setForsyningsomraade($forsyningsomraade)
    {
        $this->forsyningsomraade = $forsyningsomraade;

        return $this;
    }

    /**
     * Get placering.
     *
     * @return string
     */
    public function getPlacering()
    {
        return $this->placering;
    }

    /**
     * Set placering.
     *
     * @param string $placering
     *
     * @return PumpeDetail
     */
    public function setPlacering($placering)
    {
        $this->placering = $placering;

        return $this;
    }

    /**
     * Get applikation.
     *
     * @return string
     */
    public function getApplikation()
    {
        return $this->applikation;
    }

    /**
     * Set applikation.
     *
     * @param string $applikation
     *
     * @return PumpeDetail
     */
    public function setApplikation($applikation)
    {
        $this->applikation = $applikation;

        return $this;
    }

    /**
     * Get isoleringskappe.
     *
     * @return bool
     */
    public function getIsoleringskappe()
    {
        return $this->isoleringskappe;
    }

    /**
     * Set isoleringskappe.
     *
     * @param bool $isoleringskappe
     *
     * @return PumpeDetail
     */
    public function setIsoleringskappe($isoleringskappe)
    {
        $this->isoleringskappe = $isoleringskappe;

        return $this;
    }

    /**
     * Get bFaktor.
     *
     * @deprecated
     *
     * @return string
     */
    public function getBFaktor()
    {
        return $this->bFaktor;
    }

    /**
     * Get nyttiggjortVarme.
     *
     * @return \AppBundle\Entity\TekniskIsoleringTiltagDetail\NyttiggjortVarme
     */
    public function getNyttiggjortVarme()
    {
        return $this->nyttiggjortVarme;
    }

    /**
     * Set nyttiggjortVarme.
     *
     * @param \AppBundle\Entity\TekniskIsoleringTiltagDetail\NyttiggjortVarme $nyttiggjortVarme
     *
     * @return TekniskIsoleringTiltagDetail
     */
    public function setNyttiggjortVarme(
        \AppBundle\Entity\TekniskIsoleringTiltagDetail\NyttiggjortVarme $nyttiggjortVarme = null
    ) {
        $this->nyttiggjortVarme = $nyttiggjortVarme;

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

    /**
     * Set noter.
     *
     * @param string $noter
     *
     * @return PumpeDetail
     */
    public function setNoter($noter)
    {
        $this->noter = $noter;

        return $this;
    }

    /**
     * Get eksisterendeDrifttid.
     *
     * @return int
     */
    public function getEksisterendeDrifttid()
    {
        return $this->eksisterendeDrifttid;
    }

    /**
     * Set eksisterendeDrifttid.
     *
     * @param int $eksisterendeDrifttid
     *
     * @return PumpeDetail
     */
    public function setEksisterendeDrifttid($eksisterendeDrifttid)
    {
        $this->eksisterendeDrifttid = $eksisterendeDrifttid;

        return $this;
    }

    /**
     * Get nyDrifttid.
     *
     * @return int
     */
    public function getNyDrifttid()
    {
        return $this->nyDrifttid;
    }

    /**
     * Set nyDrifttid.
     *
     * @param int $nyDrifttid
     *
     * @return PumpeDetail
     */
    public function setNyDrifttid($nyDrifttid)
    {
        $this->nyDrifttid = $nyDrifttid;

        return $this;
    }

    /**
     * Get prisfaktor.
     *
     * @return string
     */
    public function getPrisfaktor()
    {
        return $this->prisfaktor;
    }

    /**
     * Set prisfaktor.
     *
     * @param string $prisfaktor
     *
     * @return PumpeDetail
     */
    public function setPrisfaktor($prisfaktor)
    {
        $this->prisfaktor = $prisfaktor;

        return $this;
    }

    /**
     * Get pumpe.
     *
     * @return \AppBundle\Entity\Pumpe
     */
    public function getPumpe()
    {
        return $this->pumpe;
    }

    /**
     * Set pumpe.
     *
     * @param \AppBundle\Entity\Pumpe $pumpe
     *
     * @return PumpeDetail
     */
    public function setPumpe(Pumpe $pumpe = null)
    {
        $this->pumpe = $pumpe;

        return $this;
    }

    /**
     * Get overskrevetPris.
     *
     * @return float
     */
    public function getOverskrevetPris()
    {
        return $this->overskrevetPris;
    }

    /**
     * Set overskrevetPris.
     *
     * @param float $overskrevetPris
     *
     * @return PumpeDetail
     */
    public function setOverskrevetPris($overskrevetPris)
    {
        $this->overskrevetPris = $overskrevetPris;

        return $this;
    }

    public function getVarmetabIftAekvivalentRoerstoerrelse()
    {
        return $this->varmetabIftAekvivalentRoerstoerrelse;
    }

    public function setVarmetabIftAekvivalentRoerstoerrelse($varmetabIftAekvivalentRoerstoerrelse)
    {
        $this->varmetabIftAekvivalentRoerstoerrelse = $varmetabIftAekvivalentRoerstoerrelse;

        return $this;
    }

    public function getPristillaeg()
    {
        return $this->pristillaeg;
    }

    public function getSamletInvesteringInklPristillaeg()
    {
        return $this->samletInvesteringInklPristillaeg;
    }

    public function getElforbrugVedNyeDriftstidKWhAar()
    {
        return $this->elforbrugVedNyeDriftstidKWhAar;
    }

    public function getElbespKWhAar()
    {
        return $this->elbespKWhAar;
    }

    public function getVarmebespIsokappeKWh()
    {
        return $this->varmebespIsokappeKWh;
    }

    public function getSimpelTilbagebetalingstidAar()
    {
        return $this->simpelTilbagebetalingstidAar;
    }

    public function getNutidsvaerdiSetOver15AarKr()
    {
        return $this->nutidsvaerdiSetOver15AarKr;
    }

    public function getKwhBesparelseElFraVaerket()
    {
        return $this->kwhBesparelseElFraVaerket;
    }

    public function getKwhBesparelseVarmeFraVaerket()
    {
        return $this->kwhBesparelseVarmeFraVaerket;
    }

    public function calculate()
    {
        $this->pristillaeg = $this->calculatePristillaeg();
        $this->samletInvesteringInklPristillaeg = $this->calculateSamletInvesteringInklPristillaeg();
        $this->elforbrugVedNyeDriftstidKWhAar = $this->calculateElforbrugVedNyeDriftstidKWhAar();
        $this->elbespKWhAar = $this->calculateElbespKWhAar();
        $this->varmebespIsokappeKWh = $this->calculateVarmebespIsokappeKWh();
        $this->kwhBesparelseElFraVaerket = $this->calculateKwhBesparelseElFraVaerket();
        $this->kwhBesparelseVarmeFraVaerket = $this->calculateKwhBesparelseVarmeFraVaerket();
        $this->simpelTilbagebetalingstidAar = $this->calculateSimpelTilbagebetalingstidAar();
        $this->nutidsvaerdiSetOver15AarKr = $this->calculateNutidsvaerdiSetOver15AarKr();
        parent::calculate();
    }

    public function calculatePristillaeg()
    {
        // 'AN'
        if (0 === $this->prisfaktor) {
            return 0;
        }

        return ($this->prisfaktor - 1) * $this->getPris();
    }

    public function calculateSamletInvesteringInklPristillaeg()
    {
        // 'AO'
        return $this->pristillaeg + $this->getPris();
    }

    public function calculateElforbrugVedNyeDriftstidKWhAar()
    {
        // 'AP'
        return ($this->nyDrifttid * $this->pumpe->getNytAarsforbrug()) / $this->eksisterendeDrifttid;
    }

    public function calculateElbespKWhAar()
    {
        // 'AQ'
        return ($this->pumpe->getAarsforbrug() * $this->eksisterendeDrifttid - $this->pumpe->getNytAarsforbrug() * $this->nyDrifttid) / 8760;
    }

    public function calculateVarmebespIsokappeKWh()
    {
        // 'AR'
        if ($this->isoleringskappe) {
            return 0;
        }

        if (!$this->nyttiggjortVarme) {
            return $this->bFaktor * $this->getBesparelseVedIsoleringskappe();
        }

        return $this->nyttiggjortVarme->getFaktor() * $this->getBesparelseVedIsoleringskappe();
    }

    public function calculateKwhBesparelseElFraVaerket()
    {
        // 'AU'
        if (0 === $this->elbespKWhAar && 0 === $this->varmebespIsokappeKWh) {
            return 0;
        }

        if ($this->getRapport()->getStandardforsyning()) {
            return $this->elbespKWhAar;
        }

        return $this->fordelbesparelse(
            $this->varmebespIsokappeKWh,
            $this->tiltag->getForsyningVarme(),
                'EL'
        ) + $this->elbespKWhAar;
    }

    public function calculateKwhBesparelseVarmeFraVaerket()
    {
        // 'AV'
        if (0 === $this->varmebespIsokappeKWh) {
            return 0;
        }

        if ($this->getRapport()->getStandardforsyning()) {
            return $this->varmebespIsokappeKWh;
        }

        return $this->fordelbesparelse($this->varmebespIsokappeKWh, $this->tiltag->getForsyningVarme(), 'VARME');
    }

    public function calculateSimpelTilbagebetalingstidAar()
    {
        // 'AS'
        if (0 === $this->kwhBesparelseElFraVaerket && 0 === $this->kwhBesparelseVarmeFraVaerket) {
            return 0;
        }

        return $this->divide(
            $this->samletInvesteringInklPristillaeg,
            $this->kwhBesparelseElFraVaerket * $this->getRapport()->getElKrKWh() + $this->kwhBesparelseVarmeFraVaerket * $this->getRapport()->getVarmeKrKWh()
        );
    }

    public function calculateNutidsvaerdiSetOver15AarKr()
    {
        // 'AT'
        if (0 === $this->kwhBesparelseElFraVaerket && 0 === $this->kwhBesparelseVarmeFraVaerket) {
            return 0;
        }

        return $this->nvPTO2(
            $this->samletInvesteringInklPristillaeg,
            $this->kwhBesparelseVarmeFraVaerket,
            $this->kwhBesparelseElFraVaerket,
            0,
            0,
            0,
            $this->tiltag->getLevetid(),
            1,
            0
        );
    }

    private function getPris()
    {
        if (null !== $this->overskrevetPris) {
            return $this->overskrevetPris;
        } elseif (null !== $this->pumpe) {
            return $this->pumpe->getStandInvestering();
        }

        return 0;
    }

    private function getBesparelseVedIsoleringskappe()
    {
        return $this->pumpe->getBesparelseVedIsoleringskappe($this->varmetabIftAekvivalentRoerstoerrelse);
    }
}

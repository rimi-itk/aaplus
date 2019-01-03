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
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Pumpe.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\PumpeRepository")
 */
class Pumpe
{
    use TimestampableEntity;

    public static $varmetabstabel = [
        // 'Isol. (mm' => [ 'Isolering/Diameter mm', '0 mm', '30 mm' ]
        '3/8"' => [17.2, 0.83, 0.16],
        '1/2"' => [21.3, 1.01, 0.17],
        '3/4"' => [26.9, 1.23, 0.2],
        '1"' => [33.7, 1.49, 0.23],
        '1-1/4"' => [42.4, 1.82, 0.26],
        '1-1/2"' => [48.3, 2.04, 0.28],
        '2"' => [60.3, 2.47, 0.33],
        '2-1/2"' => [76.1, 3.03, 0.39],
        '3"' => [88.9, 3.46, 0.44],
        '4"' => [114.3, 4.31, 0.54],
        '5"' => [139.7, 5.15, 0.63],
        '6"' => [168.2, 6.03, 0.74],
    ];

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
     * @ORM\Column(name="NuvaerendeType", type="string", length=255)
     */
    protected $nuvaerendeType;

    /**
     * @var int
     *
     * @ORM\Column(name="Byggemaal", type="integer")
     */
    protected $byggemaal;

    /**
     * @var string
     *
     * @ORM\Column(name="Tilslutning", type="string", length=25)
     */
    protected $tilslutning;

    /**
     * @var int
     *
     * @ORM\Column(name="Indst", type="integer")
     */
    protected $indst;

    /**
     * @var string
     *
     * @ORM\Column(name="Forbrug", type="string", length=25)
     */
    protected $forbrug;

    /**
     * @var string
     *
     * @ORM\Column(name="Q", type="decimal")
     */
    protected $q;

    /**
     * @var string
     *
     * @ORM\Column(name="H", type="decimal")
     */
    protected $h;

    /**
     * @var int
     *
     * @ORM\Column(name="Aarsforbrug", type="integer")
     */
    protected $aarsforbrug;

    /**
     * @var string
     *
     * @ORM\Column(name="NyPumpe", type="string", length=255)
     */
    protected $nyPumpe;

    /**
     * @var int
     *
     * @ORM\Column(name="NyByggemaal", type="integer")
     */
    protected $nyByggemaal;

    /**
     * @var string
     *
     * @ORM\Column(name="NyTilslutning", type="string", length=25)
     */
    protected $nyTilslutning;

    /**
     * @var string
     *
     * @ORM\Column(name="vvsnr", type="string", length=20)
     */
    protected $vvsnr;

    /**
     * @var int
     *
     * @ORM\Column(name="NytAarsforbrug", type="integer")
     */
    protected $nytAarsforbrug;

    /**
     * @var int
     *
     * @ORM\Column(name="Elbesparelse", type="integer")
     */
    protected $elbesparelse;

    /**
     * @var string
     *
     * @ORM\Column(name="Udligningssaet", type="string", length=20)
     */
    protected $udligningssaet;

    /**
     * @var string
     *
     * @ORM\Column(name="Kommentarer", type="string", length=255)
     */
    protected $kommentarer;

    /**
     * @var float
     *
     * @ORM\Column(name="StandInvestering", type="decimal", scale=4, nullable=true)
     */
    protected $standInvestering;

    /**
     * @var string
     *
     * @ORM\Column(name="Fabrikant", type="string", length=50)
     */
    protected $fabrikant;

    /**
     * @var int
     *
     * @ORM\Column(name="Roerlaengde", type="integer")
     */
    protected $roerlaengde;

    /**
     * @var string
     *
     * @ORM\Column(name="Roerstoerrelse", type="string", length=10)
     */
    protected $roerstoerrelse;

    /**
     * @var float
     */
    protected $besparelseVedIsoleringskappe;

    /**
     * Get Name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->id.'. '.$this->nuvaerendeType.' / '.$this->nyPumpe.' - Stand.inv: '.number_format(
            $this->standInvestering,
                0,
            ',',
            '.'
        ).' Kr, Indst: '.$this->indst.', Rørstr: '.$this->roerstoerrelse;
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
     * Get nuvaerendeType.
     *
     * @return string
     */
    public function getNuvaerendeType()
    {
        return $this->nuvaerendeType;
    }

    /**
     * Set nuvaerendeType.
     *
     * @param string $nuvaerendeType
     *
     * @return Pumpe
     */
    public function setNuvaerendeType($nuvaerendeType)
    {
        $this->nuvaerendeType = $nuvaerendeType;

        return $this;
    }

    /**
     * Get byggemaal.
     *
     * @return int
     */
    public function getByggemaal()
    {
        return $this->byggemaal;
    }

    /**
     * Set byggemaal.
     *
     * @param int $byggemaal
     *
     * @return Pumpe
     */
    public function setByggemaal($byggemaal)
    {
        $this->byggemaal = $byggemaal;

        return $this;
    }

    /**
     * Get tilslutning.
     *
     * @return string
     */
    public function getTilslutning()
    {
        return $this->tilslutning;
    }

    /**
     * Set tilslutning.
     *
     * @param string $tilslutning
     *
     * @return Pumpe
     */
    public function setTilslutning($tilslutning)
    {
        $this->tilslutning = $tilslutning;

        return $this;
    }

    /**
     * Get indst.
     *
     * @return int
     */
    public function getIndst()
    {
        return $this->indst;
    }

    /**
     * Set indst.
     *
     * @param int $indst
     *
     * @return Pumpe
     */
    public function setIndst($indst)
    {
        $this->indst = $indst;

        return $this;
    }

    /**
     * Get forbrug.
     *
     * @return string
     */
    public function getForbrug()
    {
        return $this->forbrug;
    }

    /**
     * Set forbrug.
     *
     * @param string $forbrug
     *
     * @return Pumpe
     */
    public function setForbrug($forbrug)
    {
        $this->forbrug = $forbrug;

        return $this;
    }

    /**
     * Get q.
     *
     * @return string
     */
    public function getQ()
    {
        return $this->q;
    }

    /**
     * Set q.
     *
     * @param string $q
     *
     * @return Pumpe
     */
    public function setQ($q)
    {
        $this->q = $q;

        return $this;
    }

    /**
     * Get h.
     *
     * @return string
     */
    public function getH()
    {
        return $this->h;
    }

    /**
     * Set h.
     *
     * @param string $h
     *
     * @return Pumpe
     */
    public function setH($h)
    {
        $this->h = $h;

        return $this;
    }

    /**
     * Get aarsforbrug.
     *
     * @return int
     */
    public function getAarsforbrug()
    {
        return $this->aarsforbrug;
    }

    /**
     * Set aarsforbrug.
     *
     * @param int $aarsforbrug
     *
     * @return Pumpe
     */
    public function setAarsforbrug($aarsforbrug)
    {
        $this->aarsforbrug = $aarsforbrug;

        return $this;
    }

    /**
     * Get nyPumpe.
     *
     * @return string
     */
    public function getNyPumpe()
    {
        return $this->nyPumpe;
    }

    /**
     * Set nyPumpe.
     *
     * @param string $nyPumpe
     *
     * @return Pumpe
     */
    public function setNyPumpe($nyPumpe)
    {
        $this->nyPumpe = $nyPumpe;

        return $this;
    }

    /**
     * Get nyByggemaal.
     *
     * @return int
     */
    public function getNyByggemaal()
    {
        return $this->nyByggemaal;
    }

    /**
     * Set nyByggemaal.
     *
     * @param int $nyByggemaal
     *
     * @return Pumpe
     */
    public function setNyByggemaal($nyByggemaal)
    {
        $this->nyByggemaal = $nyByggemaal;

        return $this;
    }

    /**
     * Get nyTilslutning.
     *
     * @return string
     */
    public function getNyTilslutning()
    {
        return $this->nyTilslutning;
    }

    /**
     * Set nyTilslutning.
     *
     * @param string $nyTilslutning
     *
     * @return Pumpe
     */
    public function setNyTilslutning($nyTilslutning)
    {
        $this->nyTilslutning = $nyTilslutning;

        return $this;
    }

    /**
     * Get vvsnr.
     *
     * @return string
     */
    public function getVvsNr()
    {
        return $this->vvsnr;
    }

    /**
     * Set vvsnr.
     *
     * @param string $vvsNr
     *
     * @return Pumpe
     */
    public function setVvsNr($vvsNr)
    {
        $this->vvsnr = $vvsNr;

        return $this;
    }

    /**
     * Get nytAarsforbrug.
     *
     * @return int
     */
    public function getNytAarsforbrug()
    {
        return $this->nytAarsforbrug;
    }

    /**
     * Set nytAarsforbrug.
     *
     * @param int $nytAarsforbrug
     *
     * @return Pumpe
     */
    public function setNytAarsforbrug($nytAarsforbrug)
    {
        $this->nytAarsforbrug = $nytAarsforbrug;

        return $this;
    }

    /**
     * Get elbesparelse.
     *
     * @return int
     */
    public function getElbesparelse()
    {
        return $this->elbesparelse;
    }

    /**
     * Set elbesparelse.
     *
     * @param int $elbesparelse
     *
     * @return Pumpe
     */
    public function setElbesparelse($elbesparelse)
    {
        $this->elbesparelse = $elbesparelse;

        return $this;
    }

    /**
     * Get udligningssaet.
     *
     * @return string
     */
    public function getUdligningssaet()
    {
        return $this->udligningssaet;
    }

    /**
     * Set udligningssaet.
     *
     * @param string $udligningssaet
     *
     * @return Pumpe
     */
    public function setUdligningssaet($udligningssaet)
    {
        $this->udligningssaet = $udligningssaet;

        return $this;
    }

    /**
     * Get kommentarer.
     *
     * @return string
     */
    public function getKommentarer()
    {
        return $this->kommentarer;
    }

    /**
     * Set kommentarer.
     *
     * @param string $kommentarer
     *
     * @return Pumpe
     */
    public function setKommentarer($kommentarer)
    {
        $this->kommentarer = $kommentarer;

        return $this;
    }

    /**
     * Get standInvestering.
     *
     * @return float
     */
    public function getStandInvestering()
    {
        return $this->standInvestering;
    }

    /**
     * Set standInvestering.
     *
     * @param float $standInvestering
     *
     * @return Pumpe
     */
    public function setStandInvestering($standInvestering)
    {
        $this->standInvestering = $standInvestering;

        return $this;
    }

    /**
     * Get fabrikant.
     *
     * @return string
     */
    public function getFabrikant()
    {
        return $this->fabrikant;
    }

    /**
     * Set fabrikant.
     *
     * @param string $fabrikant
     *
     * @return Pumpe
     */
    public function setFabrikant($fabrikant)
    {
        $this->fabrikant = $fabrikant;

        return $this;
    }

    /**
     * Get roerlaengde.
     *
     * @return int
     */
    public function getRoerlaengde()
    {
        return $this->roerlaengde;
    }

    /**
     * Set roerlaengde.
     *
     * @param int $roerlaengde
     *
     * @return Pumpe
     */
    public function setRoerlaengde($roerlaengde)
    {
        $this->roerlaengde = $roerlaengde;

        return $this;
    }

    /**
     * Get roerstoerrelse.
     *
     * @return string
     */
    public function getRoerstoerrelse()
    {
        return $this->roerstoerrelse;
    }

    /**
     * Set roerstoerrelse.
     *
     * @param string $roerstoerrelse
     *
     * @return Pumpe
     */
    public function setRoerstoerrelse($roerstoerrelse)
    {
        $this->roerstoerrelse = $roerstoerrelse;

        return $this;
    }

    /**
     * @param null|mixed $roerstoerrelse
     */
    public function getBesparelseVedIsoleringskappe($roerstoerrelse = null)
    {
        if (null === $this->besparelseVedIsoleringskappe) {
            $this->besparelseVedIsoleringskappe = $this->calculateBesparelseVedIsoleringskappe($roerstoerrelse);
        }

        return $this->besparelseVedIsoleringskappe;
    }

    private function calculateBesparelseVedIsoleringskappe($roerstoerrelse = null)
    {
        $standardtemperatur = (45 - 12);
        $varmetab = self::$varmetabstabel[null !== $roerstoerrelse ? $roerstoerrelse : $this->roerstoerrelse];

        return ($varmetab[1] - $varmetab[2]) * 2 * $standardtemperatur * 5448 / 1000;
    }
}

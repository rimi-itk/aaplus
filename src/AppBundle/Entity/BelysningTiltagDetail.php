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
use AppBundle\DBAL\Types\BelysningTiltagDetail\TiltagType;
use AppBundle\Entity\BelysningTiltagDetail\Lyskilde as BelysningTiltagDetailLyskilde;
use Doctrine\ORM\Mapping as ORM;
use Fresh\DoctrineEnumBundle\Validator\Constraints as DoctrineAssert;

/**
 * BelysningTiltagDetail.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class BelysningTiltagDetail extends TiltagDetail
{
    /**
     * @var string
     *
     * @ORM\Column(name="lokale_navn", type="string", length=255)
     */
    protected $lokale_navn;

    /**
     * @var string
     *
     * @ORM\Column(name="lokale_type", type="string", length=255, nullable=true)
     */
    protected $lokale_type;

    /**
     * @var float
     *
     * @ORM\Column(name="armaturhoejdeM", type="decimal", scale=4, nullable=true)
     */
    protected $armaturhoejdeM;

    /**
     * @var float
     *
     * @ORM\Column(name="rumstoerrelseM2", type="decimal", scale=4, nullable=true)
     */
    protected $rumstoerrelseM2;

    /**
     * @var int
     *
     * @ORM\Column(name="lokale_antal", type="integer", nullable=true)
     */
    protected $lokale_antal;

    /**
     * @var string
     *
     * @ORM\Column(name="drifttidTAar", type="integer")
     */
    protected $drifttidTAar;

    /**
     * @var BelysningTiltagDetailLyskilde
     *
     * Belysningstype
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BelysningTiltagDetail\Lyskilde")
     * ORM\JoinColumn(name="lyskilde_id", referencedColumnName="id")
     **/
    protected $lyskilde;

    /**
     * @var int
     *
     * @ORM\Column(name="lyskildeStkArmatur", type="integer")
     */
    protected $lyskildeStkArmatur;

    /**
     * @var int
     *
     * @ORM\Column(name="lyskildeWLyskilde", type="integer")
     */
    protected $lyskildeWLyskilde;

    /**
     * @var int
     *
     * @ORM\Column(name="forkoblingStkArmatur", type="integer")
     */
    protected $forkoblingStkArmatur;

    /**
     * @var int
     *
     * @ORM\Column(name="armaturerStkLokale", type="integer")
     */
    protected $armaturerStkLokale;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="elforbrugWM2", type="float")
     */
    protected $elforbrugWM2;

    /**
     * @var string
     *
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\BelysningTiltagDetail\PlaceringType")
     * @ORM\Column(name="placering", type="PlaceringType")
     **/
    protected $placering;

    /**
     * @var string
     *
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\BelysningTiltagDetail\StyringType")
     * @ORM\Column(name="styring", type="StyringType", nullable=true)
     **/
    protected $styring;

    /**
     * @var string
     *
     * This is: noterForEksisterendeBelysning
     *
     * @ORM\Column(name="noter", type="text", nullable=true)
     */
    protected $noter;

    /**
     * @var string
     *
     * @ORM\Column(name="noterForNyBelysning", type="text", nullable=true)
     */
    protected $noterForNyBelysning;

    /**
     * @var string
     *
     * @DoctrineAssert\Enum(entity="AppBundle\DBAL\Types\BelysningTiltagDetail\TiltagType")
     * @ORM\Column(name="belysningstiltag", type="TiltagType", nullable=true)
     */
    protected $belysningstiltag;

    /**
     * @var int
     *
     * @ORM\Column(name="nyeSensorerStkLokale", type="integer", nullable=true)
     */
    protected $nyeSensorerStkLokale;

    /**
     * @var float
     *
     * @ORM\Column(name="standardinvestSensorKrStk", type="decimal", scale=4, nullable=true)
     */
    protected $standardinvestSensorKrStk;

    /**
     * @var float
     *
     * @ORM\Column(name="reduktionAfDrifttid", type="decimal", scale=4, nullable=true)
     */
    protected $reduktionAfDrifttid;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="nyDriftstid", type="float")
     */
    protected $nyDriftstid;

    /**
     * @var float
     *
     * @ORM\Column(name="standardinvestArmaturKrStk", type="decimal", scale=4, nullable=true)
     */
    protected $standardinvestArmaturKrStk;

    /**
     * @var float
     *
     * @ORM\Column(name="standardinvestLyskildeKrStk", type="decimal", scale=4, nullable=true)
     */
    protected $standardinvestLyskildeKrStk;

    /**
     * @var BelysningTiltagDetailErstatningsLyskilde
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BelysningTiltagDetail\ErstatningsLyskilde")
     * ORM\JoinColumn(name="ny_erstatningslyskilde_id", referencedColumnName="id")
     */
    protected $erstatningsLyskilde;

    /**
     * @var BelysningTiltagDetailLyskilde
     *
     * Belysningstype
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BelysningTiltagDetail\Lyskilde")
     * ORM\JoinColumn(name="ny_lyskilde_id", referencedColumnName="id")
     */
    protected $nyLyskilde;

    /**
     * @var int
     *
     * @ORM\Column(name="nyLyskildeStkArmatur", type="integer", nullable=true)
     */
    protected $nyLyskildeStkArmatur;

    /**
     * @var int
     *
     * @ORM\Column(name="nyLyskildeWLyskilde", type="integer", nullable=true)
     */
    protected $nyLyskildeWLyskilde;

    /**
     * @var int
     *
     * @ORM\Column(name="nyForkoblingStkArmatur", type="integer", nullable=true)
     */
    protected $nyForkoblingStkArmatur;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="nyArmatureffektWStk", type="float")
     */
    protected $nyArmatureffektWStk;

    /**
     * @var int
     *
     * @ORM\Column(name="nyeArmaturerStkLokale", type="integer", nullable=true)
     */
    protected $nyeArmaturerStkLokale;

    /**
     * @var BelysningTiltagDetailNyStyring
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BelysningTiltagDetail\NyStyring")
     * ORM\JoinColumn(name="nyStyring_id", referencedColumnName="id", nullable=true)
     **/
    protected $nyStyring;

    /**
     * @var BelysningTiltagDetailNytArmatur
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BelysningTiltagDetail\NytArmatur", fetch="EAGER")
     * ORM\JoinColumn(name="nytArmatur_id", referencedColumnName="id", nullable=true)
     **/
    protected $nytArmatur;

    /**
     * @var float
     *
     * @ORM\Column(name="nyttiggjortVarmeAfElBesparelse", type="decimal", scale=4, nullable=true)
     */
    protected $nyttiggjortVarmeAfElBesparelse;

    /**
     * @var float
     *
     * @ORM\Column(name="prisfaktor", type="decimal", scale=4, nullable=true)
     */
    protected $prisfaktor;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="prisfaktorTillaegKrLokale", type="float")
     */
    protected $prisfaktorTillaegKrLokale;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="investeringAlleLokalerKr", type="float")
     */
    protected $investeringAlleLokalerKr;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="nytElforbrugWM2", type="float")
     */
    protected $nytElforbrugWM2;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="driftsbesparelseTilLyskilderKrAar", type="float")
     */
    protected $driftsbesparelseTilLyskilderKrAar;

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
     * @ORM\Column(name="vaegtetLevetidAar", type="float")
     */
    protected $vaegtetLevetidAar;

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
     * @ORM\Column(name="kWhBesparelseEl", type="float")
     */
    protected $kWhBesparelseEl;

    /**
     * @var float
     *
     * @Calculated
     * @ORM\Column(name="kWhBesparelseVarmeFraVarmevaerket", type="float")
     */
    protected $kWhBesparelseVarmeFraVarmevaerket;

    protected $propertiesRequiredForCalculation = [
        'lokaleNavn',
        'lyskilde',
        'placering',
        'drifttidTAar',
        'lyskildeStkArmatur',
        'lyskildeWLyskilde',
        'forkoblingStkArmatur',
        'armaturerStkLokale',
        'belysningstiltag',
        'reduktionAfDrifttid',
        'nyLyskildeStkArmatur',
        'nyLyskildeWLyskilde',
        'nyForkoblingStkArmatur',
        'prisfaktor',
    ];

    protected $udgiftSensorer;

    protected $udgiftArmaturer;

    protected $udgiftLyskilde;

    protected $levetidSensor;

    protected $armaturvaegtning;

    protected $lyskildevaegtning;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param null|belysningTiltagDetailLyskilde $lyskilde
     *                                                                 The Lyskilde
     * @param int                                $lyskildeStkArmatur
     * @param float                              $lyskildeWLyskilde
     * @param int                                $forkoblingStkArmatur
     *
     * @return float
     */
    private function __computeArmaturEffekt($lyskilde, $lyskildeStkArmatur, $lyskildeWLyskilde, $forkoblingStkArmatur)
    {
        // Z, AW
        if (!$lyskilde || 0 === $lyskildeStkArmatur || 0 === $lyskildeWLyskilde) {
            return 0;
        }

        switch ($lyskilde->getType()) {
            case 'LED-rør':
            case 'LEDpære':
                return ($lyskildeWLyskilde) * $lyskildeStkArmatur;
            case 'Hal.':
            case 'Gl':
            case 'Sp.':
            case 'LED-arm.':
                return $lyskildeWLyskilde * $lyskildeStkArmatur;
            case 'Kom. K':
                return $lyskildeStkArmatur * $lyskildeWLyskilde * 1.1817 + 2.44275 + (1.2794 * ($lyskildeStkArmatur - 1)) * 0.9432;
            case 'Hal.': // @FIXME: Duplicate case!
                return 1.0832 * $lyskildeWLyskilde + 0.192;
            default:
                switch ($lyskilde->getForkobling()) {
                    case 'konv.':
                        if ($lyskildeWLyskilde < 14.99) {
                            return 8.5 * $forkoblingStkArmatur + $lyskildeStkArmatur * $lyskildeWLyskilde;
                        } elseif ($lyskildeWLyskilde < 35.99) {
                            return 10 * $forkoblingStkArmatur + $lyskildeStkArmatur * $lyskildeWLyskilde;
                        }

                        return 12 * $forkoblingStkArmatur + $lyskildeStkArmatur * $lyskildeWLyskilde;
                    case 'hf':
                        return $forkoblingStkArmatur * 2 + $lyskildeWLyskilde * $lyskildeStkArmatur;
                    default:
                        return null;
                }
        }
    }

    /**
     * @return string
     */
    public function getNoterForNyBelysning()
    {
        return $this->noterForNyBelysning;
    }

    /**
     * @param string $noterForNyBelysning
     */
    public function setNoterForNyBelysning($noterForNyBelysning)
    {
        $this->noterForNyBelysning = $noterForNyBelysning;
    }

    /**
     * Get lokale_navn.
     *
     * @return string
     */
    public function getLokaleNavn()
    {
        return $this->lokale_navn;
    }

    /**
     * Set lokale_navn.
     *
     * @param string $lokaleNavn
     *
     * @return BelysningTiltagDetail
     */
    public function setLokaleNavn($lokaleNavn)
    {
        $this->lokale_navn = $lokaleNavn;

        return $this;
    }

    /**
     * Get lokale_type.
     *
     * @return string
     */
    public function getLokaleType()
    {
        return $this->lokale_type;
    }

    /**
     * Set lokale_type.
     *
     * @param string $lokaleType
     *
     * @return BelysningTiltagDetail
     */
    public function setLokaleType($lokaleType)
    {
        $this->lokale_type = $lokaleType;

        return $this;
    }

    /**
     * Get armaturhoejdeM.
     *
     * @return float
     */
    public function getArmaturhoejdeM()
    {
        return $this->armaturhoejdeM;
    }

    /**
     * Set armaturhoejdeM.
     *
     * @param float $armaturhoejdeM
     *
     * @return BelysningTiltagDetail
     */
    public function setArmaturhoejdeM($armaturhoejdeM)
    {
        $this->armaturhoejdeM = $armaturhoejdeM;

        return $this;
    }

    /**
     * Get rumstoerrelseM2.
     *
     * @return float
     */
    public function getRumstoerrelseM2()
    {
        return $this->rumstoerrelseM2;
    }

    /**
     * Set rumstoerrelseM2.
     *
     * @param float $rumstoerrelseM2
     *
     * @return BelysningTiltagDetail
     */
    public function setRumstoerrelseM2($rumstoerrelseM2)
    {
        $this->rumstoerrelseM2 = $rumstoerrelseM2;

        return $this;
    }

    /**
     * Get lokale_antal.
     *
     * @return int
     */
    public function getLokaleAntal()
    {
        return $this->lokale_antal;
    }

    /**
     * Set lokale_antal.
     *
     * @param int $lokaleAntal
     *
     * @return BelysningTiltagDetail
     */
    public function setLokaleAntal($lokaleAntal)
    {
        $this->lokale_antal = $lokaleAntal;

        return $this;
    }

    /**
     * Get drifttidTAar.
     *
     * @return float
     */
    public function getDrifttidTAar()
    {
        return $this->drifttidTAar;
    }

    /**
     * Set drifttidTAar.
     *
     * @param string $drifttidTAar
     *
     * @return BelysningTiltagDetail
     */
    public function setDrifttidTAar($drifttidTAar)
    {
        $this->drifttidTAar = $drifttidTAar;

        return $this;
    }

    /**
     * Get lyskildeStkArmatur.
     *
     * @return int
     */
    public function getLyskildeStkArmatur()
    {
        return $this->lyskildeStkArmatur;
    }

    /**
     * Set lyskildeStkArmatur.
     *
     * @param int $lyskildeStkArmatur
     *
     * @return BelysningTiltagDetail
     */
    public function setLyskildeStkArmatur($lyskildeStkArmatur)
    {
        $this->lyskildeStkArmatur = $lyskildeStkArmatur;

        return $this;
    }

    /**
     * Get lyskildeWLyskilde.
     *
     * @return int
     */
    public function getLyskildeWLyskilde()
    {
        return $this->lyskildeWLyskilde;
    }

    /**
     * Set lyskildeWLyskilde.
     *
     * @param int $lyskildeWLyskilde
     *
     * @return BelysningTiltagDetail
     */
    public function setLyskildeWLyskilde($lyskildeWLyskilde)
    {
        $this->lyskildeWLyskilde = $lyskildeWLyskilde;

        return $this;
    }

    /**
     * Get forkoblingStkArmatur.
     *
     * @return int
     */
    public function getForkoblingStkArmatur()
    {
        return $this->forkoblingStkArmatur;
    }

    /**
     * Set forkoblingStkArmatur.
     *
     * @param int $forkoblingStkArmatur
     *
     * @return BelysningTiltagDetail
     */
    public function setForkoblingStkArmatur($forkoblingStkArmatur)
    {
        $this->forkoblingStkArmatur = $forkoblingStkArmatur;

        return $this;
    }

    /**
     * Get armaturerStkLokale.
     *
     * @return int
     */
    public function getArmaturerStkLokale()
    {
        return $this->armaturerStkLokale;
    }

    /**
     * Set armaturerStkLokale.
     *
     * @param int $armaturerStkLokale
     *
     * @return BelysningTiltagDetail
     */
    public function setArmaturerStkLokale($armaturerStkLokale)
    {
        $this->armaturerStkLokale = $armaturerStkLokale;

        return $this;
    }

    /**
     * Get elforbrugWM2.
     *
     * @return float
     */
    public function getElforbrugWM2()
    {
        return $this->elforbrugWM2;
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
     * @return BelysningTiltagDetail
     */
    public function setPlacering($placering)
    {
        $this->placering = $placering;

        return $this;
    }

    /**
     * Get styring.
     *
     * @return string
     */
    public function getStyring()
    {
        return $this->styring;
    }

    /**
     * Set styring.
     *
     * @param string $styring
     *
     * @return BelysningTiltagDetail
     */
    public function setStyring($styring)
    {
        $this->styring = $styring;

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
     * @return BelysningTiltagDetail
     */
    public function setNoter($noter)
    {
        $this->noter = $noter;

        return $this;
    }

    /**
     * Get nyeSensorerStkLokale.
     *
     * @return int
     */
    public function getNyeSensorerStkLokale()
    {
        return $this->nyeSensorerStkLokale;
    }

    /**
     * Set nyeSensorerStkLokale.
     *
     * @param int $nyeSensorerStkLokale
     *
     * @return BelysningTiltagDetail
     */
    public function setNyeSensorerStkLokale($nyeSensorerStkLokale)
    {
        $this->nyeSensorerStkLokale = $nyeSensorerStkLokale;

        return $this;
    }

    /**
     * Get standardinvestSensorKrStk.
     *
     * @return float
     */
    public function getStandardinvestSensorKrStk()
    {
        return $this->standardinvestSensorKrStk;
    }

    /**
     * Set standardinvestSensorKrStk.
     *
     * @param string $standardinvestSensorKrStk
     *
     * @return BelysningTiltagDetail
     */
    public function setStandardinvestSensorKrStk($standardinvestSensorKrStk)
    {
        $this->standardinvestSensorKrStk = $standardinvestSensorKrStk;

        return $this;
    }

    /**
     * Get reduktionAfDrifttid.
     *
     * @return float
     */
    public function getReduktionAfDrifttid()
    {
        return $this->reduktionAfDrifttid;
    }

    /**
     * Set reduktionAfDrifttid.
     *
     * @param string $reduktionAfDrifttid
     *
     * @return BelysningTiltagDetail
     */
    public function setReduktionAfDrifttid($reduktionAfDrifttid)
    {
        $this->reduktionAfDrifttid = $reduktionAfDrifttid;

        return $this;
    }

    /**
     * Get nyDriftstid.
     *
     * @return float
     */
    public function getNyDriftstid()
    {
        return $this->nyDriftstid;
    }

    /**
     * Get standardinvestArmaturKrStk.
     *
     * @return float
     */
    public function getStandardinvestArmaturKrStk()
    {
        return $this->standardinvestArmaturKrStk;
    }

    /**
     * Set standardinvestArmaturKrStk.
     *
     * @param string $standardinvestArmaturKrStk
     *
     * @return BelysningTiltagDetail
     */
    public function setStandardinvestArmaturKrStk($standardinvestArmaturKrStk)
    {
        $this->standardinvestArmaturKrStk = $standardinvestArmaturKrStk;

        return $this;
    }

    /**
     * Get standardinvestLyskildeKrStk.
     *
     * @return float
     */
    public function getStandardinvestLyskildeKrStk()
    {
        return $this->standardinvestLyskildeKrStk;
    }

    /**
     * Set standardinvestLyskildeKrStk.
     *
     * @param string $standardinvestLyskildeKrStk
     *
     * @return BelysningTiltagDetail
     */
    public function setStandardinvestLyskildeKrStk($standardinvestLyskildeKrStk)
    {
        $this->standardinvestLyskildeKrStk = $standardinvestLyskildeKrStk;

        return $this;
    }

    /**
     * Get nyLyskildeStkArmatur.
     *
     * @return int
     */
    public function getNyLyskildeStkArmatur()
    {
        return $this->nyLyskildeStkArmatur;
    }

    /**
     * Set nyLyskildeStkArmatur.
     *
     * @param int $nyLyskildeStkArmatur
     *
     * @return BelysningTiltagDetail
     */
    public function setNyLyskildeStkArmatur($nyLyskildeStkArmatur)
    {
        $this->nyLyskildeStkArmatur = $nyLyskildeStkArmatur;

        return $this;
    }

    /**
     * Get nyLyskildeWLyskilde.
     *
     * @return int
     */
    public function getNyLyskildeWLyskilde()
    {
        return $this->nyLyskildeWLyskilde;
    }

    /**
     * Set nyLyskildeWLyskilde.
     *
     * @param int $nyLyskildeWLyskilde
     *
     * @return BelysningTiltagDetail
     */
    public function setNyLyskildeWLyskilde($nyLyskildeWLyskilde)
    {
        $this->nyLyskildeWLyskilde = $nyLyskildeWLyskilde;

        return $this;
    }

    /**
     * Get nyForkoblingStkArmatur.
     *
     * @return int
     */
    public function getNyForkoblingStkArmatur()
    {
        return $this->nyForkoblingStkArmatur;
    }

    /**
     * Set nyForkoblingStkArmatur.
     *
     * @param int $nyForkoblingStkArmatur
     *
     * @return BelysningTiltagDetail
     */
    public function setNyForkoblingStkArmatur($nyForkoblingStkArmatur)
    {
        $this->nyForkoblingStkArmatur = $nyForkoblingStkArmatur;

        return $this;
    }

    /**
     * Get nyArmatureffektWStk.
     *
     * @return float
     */
    public function getNyArmatureffektWStk()
    {
        return $this->nyArmatureffektWStk;
    }

    /**
     * Get nyeArmaturerStkLokale.
     *
     * @return int
     */
    public function getNyeArmaturerStkLokale()
    {
        return $this->nyeArmaturerStkLokale;
    }

    /**
     * Set nyeArmaturerStkLokale.
     *
     * @param int $nyeArmaturerStkLokale
     *
     * @return BelysningTiltagDetail
     */
    public function setNyeArmaturerStkLokale($nyeArmaturerStkLokale)
    {
        $this->nyeArmaturerStkLokale = $nyeArmaturerStkLokale;

        return $this;
    }

    /**
     * Get nyttiggjortVarmeAfElBesparelse.
     *
     * @return float
     */
    public function getNyttiggjortVarmeAfElBesparelse()
    {
        return $this->nyttiggjortVarmeAfElBesparelse;
    }

    /**
     * Set nyttiggjortVarmeAfElBesparelse.
     *
     * @param string $nyttiggjortVarmeAfElBesparelse
     *
     * @return BelysningTiltagDetail
     */
    public function setNyttiggjortVarmeAfElBesparelse($nyttiggjortVarmeAfElBesparelse)
    {
        $this->nyttiggjortVarmeAfElBesparelse = $nyttiggjortVarmeAfElBesparelse;

        return $this;
    }

    /**
     * Get prisfaktor.
     *
     * @return float
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
     * @return BelysningTiltagDetail
     */
    public function setPrisfaktor($prisfaktor)
    {
        $this->prisfaktor = $prisfaktor;

        return $this;
    }

    /**
     * Get prisfaktorTillaegKrLokale.
     *
     * @return float
     */
    public function getPrisfaktorTillaegKrLokale()
    {
        return $this->prisfaktorTillaegKrLokale;
    }

    /**
     * Get investeringAlleLokalerKr.
     *
     * @return float
     */
    public function getInvesteringAlleLokalerKr()
    {
        return $this->investeringAlleLokalerKr;
    }

    /**
     * Get nytElforbrugWM2.
     *
     * @return float
     */
    public function getNytElforbrugWM2()
    {
        return $this->nytElforbrugWM2;
    }

    /**
     * Get driftsbesparelseTilLyskilderKrAar.
     *
     * @return float
     */
    public function getDriftsbesparelseTilLyskilderKrAar()
    {
        return $this->driftsbesparelseTilLyskilderKrAar;
    }

    /**
     * Get simpelTilbagebetalingstidAar.
     *
     * @return float
     */
    public function getSimpelTilbagebetalingstidAar()
    {
        return $this->simpelTilbagebetalingstidAar;
    }

    /**
     * Get vaegtetLevetidAar.
     *
     * @return float
     */
    public function getVaegtetLevetidAar()
    {
        return $this->vaegtetLevetidAar;
    }

    /**
     * Get nutidsvaerdiSetOver15AarKr.
     *
     * @return float
     */
    public function getNutidsvaerdiSetOver15AarKr()
    {
        return $this->nutidsvaerdiSetOver15AarKr;
    }

    /**
     * Get kWhBesparelseEl.
     *
     * @return float
     */
    public function getKwhBesparelseEl()
    {
        return $this->kWhBesparelseEl;
    }

    /**
     * Get kWhBesparelseVarmeFraVarmevaerket.
     *
     * @return float
     */
    public function getKwhBesparelseVarmeFraVarmevaerket()
    {
        return $this->kWhBesparelseVarmeFraVarmevaerket;
    }

    /**
     * @return BelysningTiltagDetailNytArmatur
     */
    public function getNytArmatur()
    {
        return $this->nytArmatur;
    }

    /**
     * @param BelysningTiltagDetailNytArmatur $nytArmatur
     */
    public function setNytArmatur($nytArmatur)
    {
        $this->nytArmatur = $nytArmatur;
    }

    /**
     * @return BelysningTiltagDetailErstatningsLyskilde
     */
    public function getErstatningsLyskilde()
    {
        return $this->erstatningsLyskilde;
    }

    /**
     * @param BelysningTiltagDetailErstatningsLyskilde $erstatningsLyskilde
     */
    public function setErstatningsLyskilde($erstatningsLyskilde)
    {
        $this->erstatningsLyskilde = $erstatningsLyskilde;
    }

    public function getPropertiesRequiredForCalculation()
    {
        $properties = parent::getPropertiesRequiredForCalculation();

        if ($this->getNyStyring()) {
            $properties[] = 'nyeSensorerStkLokale';
            $properties[] = 'standardinvestSensorKrStk';
        }

        $tiltag = $this->getBelysningstiltag();
        switch ($tiltag) {
            case TiltagType::ARMATUR:
                $properties[] = 'nytArmatur';
                $properties[] = 'nyeArmaturerStkLokale';
                $properties[] = 'standardinvestArmaturKrStk';

                break;
            case TiltagType::LED_I_EKSIST_ARM:
            case TiltagType::NY_INDSATS_I_ARM:
                $properties[] = 'erstatningsLyskilde';
                $properties[] = 'standardinvestLyskildeKrStk';

                break;
        }

        return $properties;
    }

    /**
     * @return BelysningTiltagDetailNyStyring
     */
    public function getNyStyring()
    {
        return $this->nyStyring;
    }

    /**
     * @param BelysningTiltagDetailNyStyring $nyStyring
     */
    public function setNyStyring($nyStyring)
    {
        $this->nyStyring = $nyStyring;
    }

    /**
     * Get belysningtiltag.
     *
     * @return string
     */
    public function getBelysningstiltag()
    {
        return $this->belysningstiltag;
    }

    /**
     * Set tiltag.
     *
     * @param string $belysningstiltag
     *
     * @return BelysningTiltagDetail
     */
    public function setBelysningstiltag($belysningstiltag)
    {
        $this->belysningstiltag = $belysningstiltag;

        return $this;
    }

    public function calculate()
    {
        $this->elforbrugWM2 = $this->calculateElforbrugWM2();
        $this->nyDriftstid = $this->calculateNyDriftstid();
        $this->nyArmatureffektWStk = $this->calculateNyArmatureffektWStk();
        $this->prisfaktorTillaegKrLokale = $this->calculatePrisfaktorTillaegKrLokale();
        $this->investeringAlleLokalerKr = $this->calculateInvesteringAlleLokalerKr();
        $this->nytElforbrugWM2 = $this->calculateNytElforbrugWM2();
        $this->driftsbesparelseTilLyskilderKrAar = $this->calculateDriftsbesparelseTilLyskilderKrAar();
        $this->kWhBesparelseEl = $this->calculateKwhBesparelseEl();
        $this->kWhBesparelseVarmeFraVarmevaerket = $this->calculateKwhBesparelseVarmeFraVarmevaerket();
        $this->simpelTilbagebetalingstidAar = $this->calculateSimpelTilbagebetalingstidAar();
        $this->vaegtetLevetidAar = $this->calculateVaegtetLevetidAar();
        $this->nutidsvaerdiSetOver15AarKr = $this->calculateNutidsvaerdiSetOver15AarKr();
        parent::calculate();
    }

    /**
     * Get lyskilde.
     *
     * @return BelysningTiltagDetailLyskilde
     */
    public function getLyskilde()
    {
        return $this->lyskilde;
    }

    /**
     * Set lyskilde.
     *
     * @param BelysningTiltagDetailLyskilde $lyskilde
     *
     * @return BelysningTiltagDetail
     */
    public function setLyskilde(BelysningTiltagDetailLyskilde $lyskilde = null)
    {
        $this->lyskilde = $lyskilde;

        return $this;
    }

    /**
     * Get nyLyskilde.
     *
     * @see getLyskilde()
     *
     * @return BelysningTiltagDetailLyskilde
     */
    public function getNyLyskilde()
    {
        return $this->nyLyskilde;
    }

    /**
     * Set nyLyskilde.
     *
     * @param BelysningTiltagDetailLyskilde $nyLyskilde
     *
     * @return BelysningTiltagDetail
     */
    public function setNyLyskilde($nyLyskilde = null)
    {
        $this->nyLyskilde = $nyLyskilde;

        return $this;
    }

    public function getUdgiftSensorer()
    {
        if (null === $this->udgiftSensorer) {
            $this->udgiftSensorer = $this->_computeUdgiftSensorer();
        }

        return $this->udgiftSensorer;
    }

    public function getLevetidSensor()
    {
        if (null === $this->levetidSensor) {
            $this->levetidSensor = $this->_computeLevetidSensor();
        }

        return $this->levetidSensor;
    }

    public function getArmaturvaegtning()
    {
        if (null === $this->armaturvaegtning) {
            $this->armaturvaegtning = $this->getUdgiftArmaturer() * $this->_computeLevetidArmatur();
        }

        return $this->armaturvaegtning;
    }

    public function getUdgiftArmaturer()
    {
        if (null === $this->udgiftArmaturer) {
            $this->udgiftArmaturer = $this->_computeUdgiftArmatur();
        }

        return $this->udgiftArmaturer;
    }

    public function getLyskildevaegtning()
    {
        if (null === $this->lyskildevaegtning) {
            $this->lyskildevaegtning = $this->getUdgiftLyskilde() * $this->_computeLevetidLyskilde();
        }

        return $this->lyskildevaegtning;
    }

    public function getUdgiftLyskilde()
    {
        if (null === $this->udgiftLyskilde) {
            $this->udgiftLyskilde = $this->_computeUdgiftLyskilde();
        }

        return $this->udgiftLyskilde;
    }

    private function calculateElforbrugWM2()
    {
        // AC
        $armaturEffekt = $this->_computeArmaturEffekt($this->getLyskilde(true));

        if (null === $this->rumstoerrelseM2 || 0 === $this->rumstoerrelseM2 || 0 === $armaturEffekt || 0 === $this->armaturerStkLokale) {
            return 0;
        }

        return $armaturEffekt * $this->armaturerStkLokale / $this->rumstoerrelseM2;
    }

    private function _computeArmaturEffekt()
    {
        return $this->__computeArmaturEffekt(
            $this->getLyskilde(true),
            $this->lyskildeStkArmatur,
            $this->lyskildeWLyskilde,
            $this->forkoblingStkArmatur
        );
    }

    private function calculateNyDriftstid()
    {
        // AN
        if (0 === $this->drifttidTAar || TiltagType::NONE === $this->belysningstiltag) {
            return 0;
        }

        return $this->drifttidTAar - $this->reduktionAfDrifttid * $this->drifttidTAar;
    }

    private function calculateNyArmatureffektWStk()
    {
        // AW
        return $this->__computeArmaturEffekt(
            $this->getNyLyskilde(true),
            $this->nyLyskildeStkArmatur,
            $this->nyLyskildeWLyskilde,
            $this->nyForkoblingStkArmatur
        );
    }

    private function calculatePrisfaktorTillaegKrLokale()
    {
        // BA
        if (0 === $this->prisfaktor) {
            return 0;
        }

        return ($this->nyeSensorerStkLokale * $this->standardinvestSensorKrStk
                + $this->standardinvestArmaturKrStk * $this->nyeArmaturerStkLokale
                + $this->standardinvestLyskildeKrStk * $this->nyLyskildeStkArmatur)
            * ($this->prisfaktor - 1);
    }

    private function calculateInvesteringAlleLokalerKr()
    {
        // BB
        $nyLyskilde = $this->getNyLyskilde(true);
        if (!$nyLyskilde || !$this->lokale_antal) {
            return 0;
        } elseif ('LED-arm.' === $nyLyskilde->getType()) {
            return ($this->nyeSensorerStkLokale * $this->standardinvestSensorKrStk
                    + $this->standardinvestArmaturKrStk * $this->nyeArmaturerStkLokale
                    + $this->prisfaktorTillaegKrLokale) * $this->lokale_antal;
        }

        return ($this->nyeSensorerStkLokale * $this->standardinvestSensorKrStk
                + $this->standardinvestArmaturKrStk * $this->nyeArmaturerStkLokale
                + $this->standardinvestLyskildeKrStk * $this->nyLyskildeStkArmatur * $this->nyeArmaturerStkLokale
                + $this->prisfaktorTillaegKrLokale) * $this->lokale_antal;
    }

    private function calculateNytElforbrugWM2()
    {
        // BD
        if (null === $this->rumstoerrelseM2 || 0 === $this->rumstoerrelseM2) {
            return 0;
        }

        if (0 === $this->nyArmatureffektWStk) {
            return $this->elforbrugWM2;
        }

        return $this->nyArmatureffektWStk * $this->nyeArmaturerStkLokale / $this->rumstoerrelseM2;
    }

    private function calculateDriftsbesparelseTilLyskilderKrAar()
    {
        // BK
        $lyskilde = $this->getLyskilde(true);
        $nyLyskilde = $this->getNyLyskilde(true);

        $nyLyskildeLevetid = 0;
        $nyLyskildeUdgift = 0;
        if ($nyLyskilde) {
            $nyLyskildeLevetid = $nyLyskilde->getLevetid();
            $nyLyskildeUdgift = $nyLyskilde->getUdgift();
        } elseif ($this->nyeSensorerStkLokale && $lyskilde) {
            $nyLyskildeLevetid = $lyskilde->getLevetid();
            $nyLyskildeUdgift = $lyskilde->getUdgift();
        }

        if (!$this->lokale_antal || !$lyskilde || 0 === $lyskilde->getLevetid() || 0 === $nyLyskildeLevetid) {
            return 0;
        }

        return ($this->lyskildeStkArmatur * $this->armaturerStkLokale * $lyskilde->getUdgift() * $this->drifttidTAar / $lyskilde->getLevetid()
                - $this->nyLyskildeStkArmatur * $this->nyeArmaturerStkLokale * $nyLyskildeUdgift * $this->nyDriftstid / $nyLyskildeLevetid)
            * $this->lokale_antal;
    }

    private function calculateKwhBesparelseEl()
    {
        // BT
        $elbesparelse = $this->_computeElbesparelseAlleLokaler();
        $varmebesparelse = $this->_computeVarmebesparelseAlleLokaler();

        if (0 === $elbesparelse && 0 === $varmebesparelse) {
            return 0;
        } elseif ($this->getRapport()->getStandardforsyning()) {
            return $elbesparelse;
        }

        return $this->fordelbesparelse($varmebesparelse, $this->tiltag->getForsyningVarme(), 'EL') + $elbesparelse;
    }

    private function _computeElbesparelseAlleLokaler()
    {
        // BE
        $computeElforbrugPrLokale = function () {
            // AB
            $armaturEffekt = $this->_computeArmaturEffekt($this->getLyskilde(true));
            if (0 === $armaturEffekt || 0 === $this->armaturerStkLokale) {
                return 0;
            }

            return $armaturEffekt * $this->drifttidTAar * $this->armaturerStkLokale / 1000;
        };

        $computeNytElforbrugPrLokale = function () {
            // BC
            if (0 === $this->nyDriftstid) {
                return 0;
            } elseif (0 === $this->nyArmatureffektWStk) {
                $armaturEffekt = $this->_computeArmaturEffekt($this->getLyskilde(true));

                return $armaturEffekt * $this->nyDriftstid * $this->armaturerStkLokale / 1000;
            }

            return $this->nyArmatureffektWStk * $this->nyDriftstid * $this->nyeArmaturerStkLokale / 1000;
        };

        $elforbrug = $computeElforbrugPrLokale();
        $nytElforbrug = $computeNytElforbrugPrLokale();

        if (0 === $elforbrug || 0 === $nytElforbrug || 0 === $this->lokale_antal || null === $this->lokale_antal) {
            return 0;
        }

        return ($elforbrug - $nytElforbrug) * $this->lokale_antal;
    }

    private function _computeVarmebesparelseAlleLokaler()
    {
        // BF
        $elbesparelse = $this->_computeElbesparelseAlleLokaler();
        if (0 === $elbesparelse) {
            return 0;
        }

        return $elbesparelse * -$this->nyttiggjortVarmeAfElBesparelse;
    }

    private function calculateKwhBesparelseVarmeFraVarmevaerket()
    {
        // BU
        $varmebesparelse = $this->_computeVarmebesparelseAlleLokaler();
        if (0 === $varmebesparelse) {
            return 0;
        } elseif ($this->getRapport()->getStandardforsyning()) {
            return $varmebesparelse;
        }

        return $this->fordelbesparelse($varmebesparelse, $this->tiltag->getForsyningVarme(), 'VARME');
    }

    private function calculateSimpelTilbagebetalingstidAar()
    {
        // BL
        if (0 === $this->investeringAlleLokalerKr) {
            return 0;
        }

        return $this->divide(
            $this->investeringAlleLokalerKr,
            $this->kWhBesparelseEl * $this->getRapport()->getElKrKWh() + $this->kWhBesparelseVarmeFraVarmevaerket * $this->getRapport()->getVarmeKrKWh() + $this->driftsbesparelseTilLyskilderKrAar
        );
    }

    private function calculateVaegtetLevetidAar()
    {
        // BM
        if (0 === $this->investeringAlleLokalerKr) {
            return 0;
        } elseif (null === $this->nyLyskilde) {
            return 10;
        }

        $udgiftSensorer = $this->_computeUdgiftSensorer();
        $levetidSensor = $this->_computeLevetidSensor();
        $udgiftArmatur = $this->_computeUdgiftArmatur();
        $levetidArmatur = $this->_computeLevetidArmatur();
        $udgiftLyskilde = $this->_computeUdgiftLyskilde();
        $levetidLyskilde = $this->_computeLevetidLyskilde();

        return $this->divide(
            $udgiftSensorer * $levetidSensor + $udgiftArmatur * $levetidArmatur + $udgiftLyskilde * $levetidLyskilde,
            $udgiftSensorer + $udgiftArmatur + $udgiftLyskilde
        );
    }

    private function _computeUdgiftSensorer()
    {
        // BN
        if (0 === $this->nyeSensorerStkLokale || null === $this->lokale_antal) {
            return 0;
        }

        return $this->nyeSensorerStkLokale * $this->standardinvestSensorKrStk * $this->prisfaktor * $this->lokale_antal;
    }

    private function _computeLevetidSensor()
    {
        return 10;
    }

    private function _computeUdgiftArmatur()
    {
        // BO
        if (0 === $this->standardinvestArmaturKrStk || null === $this->lokale_antal) {
            return 0;
        }

        return $this->standardinvestArmaturKrStk * $this->nyeArmaturerStkLokale * $this->lokale_antal * $this->prisfaktor;
    }

    private function _computeLevetidArmatur()
    {
        // BQ
        $nyLyskilde = $this->getNyLyskilde(true);
        $levetid = $nyLyskilde ? $nyLyskilde->getLevetid() : 0;

        if (0 === $levetid || 0 === $this->nyDriftstid) {
            return 0;
        }

        return min(25, $levetid / $this->nyDriftstid);
    }

    private function _computeUdgiftLyskilde()
    {
        // BP
        if (0 === $this->standardinvestLyskildeKrStk) {
            return 0;
        }

        return $this->standardinvestLyskildeKrStk * $this->nyLyskildeStkArmatur * $this->prisfaktor;
    }

    private function _computeLevetidLyskilde()
    {
        // BR
        return $this->_computeLevetidArmatur();
    }

    private function calculateNutidsvaerdiSetOver15AarKr()
    {
        // BV
        $faktorForReinvestering = $this->_computeFaktorForReinvestering();
        if (0 === $this->vaegtetLevetidAar || 0 === $faktorForReinvestering) {
            return 0;
        } elseif (null === $this->getNyLyskilde(true)) {
            return $this->nvPTO2(
                $this->investeringAlleLokalerKr,
                $this->kWhBesparelseVarmeFraVarmevaerket,
                $this->kWhBesparelseEl,
                0,
                0,
                0,
                round($this->vaegtetLevetidAar),
                $faktorForReinvestering,
                0
            );
        }

        return $this->nvPTO2(
            $this->investeringAlleLokalerKr,
            $this->kWhBesparelseVarmeFraVarmevaerket,
            $this->kWhBesparelseEl,
            0,
            $this->driftsbesparelseTilLyskilderKrAar,
            0,
            round($this->vaegtetLevetidAar),
            $faktorForReinvestering,
            0
        );
    }

    private function _computeFaktorForReinvestering()
    {
        return 1;
    }
}

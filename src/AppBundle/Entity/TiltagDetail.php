<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use AppBundle\Calculation\Calculation;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * TiltagDetail.
 *
 * @ORM\Table()
 * @InheritanceType("SINGLE_TABLE")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap({
 *    "pumpe" = "PumpeTiltagDetail",
 *    "special" = "SpecialTiltagDetail",
 *    "belysning" = "BelysningTiltagDetail",
 *    "klimaskærm" = "KlimaskaermTiltagDetail",
 *    "vindue" = "VindueTiltagDetail",
 *    "tekniskisolering" = "TekniskIsoleringTiltagDetail",
 *    "solcelle" = "SolcelleTiltagDetail",
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Entity\TiltagDetailRepository")
 * @JMS\Discriminator(field = "_discr", map = {
 *    "pumpe": "AppBundle\Entity\PumpeTiltagDetail",
 *    "special": "AppBundle\Entity\SpecialTiltagDetail",
 *    "belysning" = "AppBundle\Entity\BelysningTiltagDetail",
 *    "klimaskærm" = "AppBundle\Entity\KlimaskaermTiltagDetail",
 *    "tekniskisolering" = "AppBundle\Entity\TekniskIsoleringTiltagDetail",
 *    "solcelle" = "AppBundle\Entity\SolcelleTiltagDetail",
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class TiltagDetail
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
     * @var Tiltag
     *
     * @ManyToOne(targetEntity="Tiltag", inversedBy="details")
     * @JoinColumn(name="tiltag_id", referencedColumnName="id")
     * @JMS\Type("AppBundle\Entity\Tiltag")
     **/
    protected $tiltag;

    /**
     * @var bool
     *
     * @ORM\Column(name="ikkeElenaBerettiget", type="boolean", nullable=true)
     */
    protected $ikkeElenaBerettiget = false;

    /**
     * @var string
     *
     * @ORM\Column(name="Title", type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @var bool
     *
     * @ORM\Column(name="tilvalgt", type="boolean", nullable=true)
     */
    protected $tilvalgt = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="laastAfEnergiraadgiver", type="boolean", nullable=true)
     */
    protected $laastAfEnergiraadgiver;

    /**
     * @var Configuration
     */
    protected $configuration;

    protected $propertiesRequiredForCalculation = [];

    // Temp field for batch edit form - not persisted
    protected $batchEdit = false;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function __toString()
    {
        $className = \get_class($this);
        if (preg_match('@\\\\(?<name>[^\\\\]+)$@', $className, $matches)) {
            return $matches['name'];
        }

        return $className;
    }

    /**
     * Initialize a new TiltagDetail.
     *
     * Can be used for setting default values, say.
     */
    public function init(Tiltag $tiltag)
    {
    }

    /**
     * Get ikkeelenaberettiget.
     *
     * @return bool
     */
    public function getIkkeElenaBerettiget()
    {
        return $this->ikkeElenaBerettiget;
    }

    /**
     * Set ikkeElenaBerettiget.
     *
     * @param string ikkeElenaBerettiget
     * @param mixed $ikkeElenaBerettiget
     *
     * @return Bygning
     */
    public function setIkkeElenaBerettiget($ikkeElenaBerettiget)
    {
        $this->ikkeElenaBerettiget = $ikkeElenaBerettiget;

        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get tilvalgt.
     *
     * @return bool
     */
    public function getTilvalgt()
    {
        return $this->tilvalgt;
    }

    /**
     * Set tilvalgt.
     *
     * @param bool $tilvalgt
     *
     * @return TiltagDetail
     */
    public function setTilvalgt($tilvalgt)
    {
        $this->tilvalgt = $tilvalgt;

        return $this;
    }

    /**
     * Get laastAfEnergiraadgiver.
     *
     * @return bool
     */
    public function getLaastAfEnergiraadgiver()
    {
        return $this->laastAfEnergiraadgiver;
    }

    /**
     * Set laastAfEnergiraadgiver.
     *
     * @param bool $laastAfEnergiraadgiver
     *
     * @return KlimaskaermTiltagDetail
     */
    public function setLaastAfEnergiraadgiver($laastAfEnergiraadgiver)
    {
        $this->laastAfEnergiraadgiver = $laastAfEnergiraadgiver;

        return $this;
    }

    /**
     * Get the rapport (convenience method).
     *
     * @return Rapport
     */
    public function getRapport()
    {
        return $this->getTiltag()->getRapport();
    }

    /**
     * Get tiltag.
     *
     * @return \AppBundle\Entity\tiltag
     */
    public function getTiltag()
    {
        return $this->tiltag;
    }

    /**
     * Set tiltag.
     *
     * @param \AppBundle\Entity\tiltag $tiltag
     *
     * @return Rapport
     */
    public function setTiltag(Tiltag $tiltag = null)
    {
        if ($this->tiltag && $this->tiltag !== $tiltag) {
            $this->tiltag->removeDetail($this);
        }
        if ($tiltag) {
            $tiltag->addDetail($this);
        }
        $this->tiltag = $tiltag;

        return $this;
    }

    /**
     * Get all files on this TiltagDetail.
     *
     * @return array
     */
    public function getAllFiles()
    {
        return null;
    }

    /**
     * Handle uploads.
     *
     * @param $manager
     */
    public function handleUploads($manager)
    {
    }

    /**
     * Check if calculating this Tiltag makes sense.
     * Some values may be required to make a meaningful calculation.
     *
     * @param mixed $messages
     */
    public function getCalculationWarnings($messages = [])
    {
        $properties = $this->getPropertiesRequiredForCalculation();
        $prefix = strtolower((string) $this);
        $d = Calculation::getCalculationWarnings($this, $properties, $prefix);

        return Calculation::getCalculationWarnings($this, $properties, $prefix);
    }

    public function getPropertiesRequiredForCalculation()
    {
        return $this->propertiesRequiredForCalculation;
    }

    /**
     * Get index.
     *
     * @return int
     */
    public function getIndexNumber()
    {
        $details = $this->getTiltag()->getDetails();
        $index = 1;
        foreach ($details as $d) {
            if ($this->getId() === $d->getId()) {
                return $index;
            }
            ++$index;
        }

        return 0;
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

    /**
     * Calculate stuff.
     */
    public function calculate()
    {
    }

    /**
     * @return bool
     */
    public function isBatchEdit()
    {
        return $this->batchEdit;
    }

    /**
     * @param bool $batchEdit
     */
    public function setBatchEdit($batchEdit)
    {
        $this->batchEdit = $batchEdit;
    }

    public function updateProperties($detail)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        if (\get_class($this) === \get_class($detail)) {
            foreach ($detail as $property => $value) {
                // Only update set values
                if (null !== $value && $accessor->isWritable($this, $property)) {
                    $accessor->setValue($this, $property, $value);
                }
            }
        }
    }

    protected function fordelbesparelse($BesparKwh, $kilde, $type)
    {
        return Calculation::fordelbesparelse($BesparKwh, $kilde, $type);
    }

    protected function nvPTO2(
        $Invest,
        $BesparKwhVarme,
        $BesparKwhEl,
        $Besparm3Vand,
        $DogV,
        $Straf,
        $Levetid,
        $FaktorReInvest,
        $SalgAfEnergibesparelse
    ) {
        $rapport = $this->tiltag->getRapport();
        $Kalkulationsrente = $rapport->getKalkulationsrente();
        $Inflationsfaktor = $rapport->getInflationsfaktor();
        $Inflation = $rapport->getInflation();
        $Lobetid = $rapport->getLobetid();
        $Elfaktor = $rapport->getElfaktor();
        $Varmefaktor = $rapport->getVarmefaktor();
        $Vandfaktor = $rapport->getVandfaktor();

        $Reinvest = 0;
        $AntalReinvest = 0;
        $Scrapvaerdi = 0;

        if ($Levetid > 0) {
            if ($Levetid < $Lobetid) { // 'reinvestering foretages
                $AntalReinvest = floor($Lobetid / $Levetid);

                if (1 === $AntalReinvest) {
                    $Reinvest = ($Invest * $FaktorReInvest * pow(
                        1 + $Inflation,
                                $Levetid + 1
                    )) / pow(1 + $Kalkulationsrente, $Levetid + 1);
                } elseif ($AntalReinvest > 1) { // 'kan evt. forbedres til mere statisk formel aht. beregningshastigheden
                    for ($x = 1; $x <= $AntalReinvest; ++$x) {
                        $Reinvest = $Reinvest + ($Invest * $FaktorReInvest * pow(
                            1 + $Inflation,
                                    $Levetid * $x + 1
                        )) / (pow(1 + $Kalkulationsrente, $Levetid * $x + 1));
                    }
                }
            }

            if ($Levetid > $Lobetid) { // 'ingen reinvesteringer
                $Scrapvaerdi = (1 - ($Lobetid / $Levetid)) * $Invest * pow(1 + $Inflation, $Lobetid);
            } elseif (0 === $Lobetid - $AntalReinvest * $Levetid) {
                $Scrapvaerdi = 0;
            } else {
                $Scrapvaerdi = (1 - ($Lobetid - $AntalReinvest * $Levetid) / $Levetid) * $Invest * $FaktorReInvest * pow(
                    1 + $Inflation,
                        $Lobetid
                );
            }
            // $Scrapvaerdi is defined as long in Excel.
            $Scrapvaerdi = round($Scrapvaerdi);
        }

        return ((-$Invest + $SalgAfEnergibesparelse) / (1 + $Kalkulationsrente)) + $BesparKwhVarme * $Varmefaktor + $BesparKwhEl * $Elfaktor + $Besparm3Vand * $Vandfaktor + ($Scrapvaerdi / pow(
            1 + $Kalkulationsrente,
                    $Lobetid
        )) + ($DogV + $Straf) * $Inflationsfaktor - $Reinvest;
    }

    /*
Public Function NvPTO2(Invest As Single, BesparKwhVarme As Single, BesparKwhEl As Single, Besparm3Vand As Single, DogV As Single, Straf As Single, Levetid As Single, FaktorReInvest As Single, SalgAfEnergibesparelse)
Dim Kalkulationsrente As Single
Dim Inflationsfaktor As Single
Dim Inflation As Single
Dim Lobetid As Integer
Dim AntalReinvest As Integer
Dim Elfaktor As Single
Dim Varmefaktor As Single
Dim Vandfaktor As Single
Dim Scrapvaerdi As Long
Dim Reinvest As Single
Dim x As Integer

Kalkulationsrente = Worksheets("1.Tiltagslisterådgiver").Range("ai23").Value
Inflationsfaktor = Worksheets("1.Tiltagslisterådgiver").Range("ai26")
Inflation = Worksheets("1.Tiltagslisterådgiver").Range("ak23")
Lobetid = Worksheets("1.Tiltagslisterådgiver").Range("an23")
Elfaktor = Worksheets("1.TiltagslisteRådgiver").Range("ah25") 'tilbagediskonterede faktorer for energi-priser over 15 år
Varmefaktor = Worksheets("1.TiltagslisteRådgiver").Range("ah24")
Vandfaktor = Worksheets("1.TiltagslisteRådgiver").Range("ai27")

Reinvest = 0
AntalReinvest = 0

If Levetid > 0 Then
If Levetid < Lobetid Then 'reinvestering foretages
      AntalReinvest = Application.RoundDown(Lobetid / Levetid, 0)

      If AntalReinvest = 1 Then
          Reinvest = (Invest * FaktorReInvest * (1 + Inflation) ^ (Levetid + 1)) / ((1 + Kalkulationsrente) ^ (Levetid + 1))
      ElseIf AntalReinvest > 1 Then 'kan evt. forbedres til mere statisk formel aht. beregningshastigheden
          For x = 1 To AntalReinvest
              Reinvest = Reinvest + (Invest * FaktorReInvest * (1 + Inflation) ^ ((Levetid * x) + 1)) / ((1 + Kalkulationsrente) ^ ((Levetid * x) + 1))
          Next
      End If

End If


If Levetid > Lobetid Then 'ingen reinvesteringer
      Scrapvaerdi = (1 - (Lobetid / Levetid)) * Invest * (1 + Inflation) ^ Lobetid
ElseIf (Lobetid - AntalReinvest * Levetid) = 0 Then
     Scrapvaerdi = 0
Else
      Scrapvaerdi = (1 - (Lobetid - AntalReinvest * Levetid) / Levetid) * Invest * FaktorReInvest * (1 + Inflation) ^ Lobetid
End If
End If

      NvPTO2 = ((-Invest + SalgAfEnergibesparelse) / (1 + Kalkulationsrente) ^ 1) + BesparKwhVarme * Varmefaktor + BesparKwhEl * Elfaktor + Besparm3Vand * Vandfaktor + (Scrapvaerdi / (1 + Kalkulationsrente) ^ Lobetid) + (DogV + Straf) * Inflationsfaktor - Reinvest
End Function
*/

    /**
     * Safe division.
     *
     * @param float $numerator
     *                           The numerator
     * @param float $denominator
     *                           The denominator
     *
     * @return float
     */
    protected function divide($numerator, $denominator)
    {
        return Calculation::divide($numerator, $denominator);
    }
}

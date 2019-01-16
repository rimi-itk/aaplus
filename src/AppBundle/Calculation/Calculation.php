<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Calculation;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class Calculation
{
    protected $container;
    private static $allowedDeviance = 0.0001;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Check if two numeric values are equals within some deviance.
     *
     * @param float $a
     *                               A value
     * @param float $b
     *                               Another value
     * @param float $allowedDeviance (optional)
     *                               The allowed deviance (a number between 0 and 1)
     *
     * @return bool
     *              True iff the values are equal within the allowed deviance
     */
    public static function areEqual($a, $b, $allowedDeviance = null)
    {
        $delta = abs(min($a, $b) * (null === $allowedDeviance ? self::$allowedDeviance : $allowedDeviance));
        if (abs($a - $b) > $delta) {
            return false;
        }

        return true;
    }

    /**
     * Decide if any calculated values (numeric only) in entity will have different values if re-calculated.
     *
     * @FIXME:
     *
     * @param object $entity.
     *                        The entity.
     *
     * @return array of string
     *               Whether any numeric value will if re-calculating values
     */
    public function getChanges($entity)
    {
        $changes = [];
        $calculatedFields = $this->getCalculatedFields($entity);

        if ($calculatedFields) {
            $old = $entity;
            $new = $this->calculate(clone $old);

            foreach ($calculatedFields as $field) {
                try {
                    $name = $field['fieldName'];
                    $type = $field['type'];
                    $getter = 'get'.$name;
                    if (method_exists($old, $getter)) {
                        $oldValue = $old->{$getter}();
                        $newValue = $new->{$getter}();

                        if (\is_array($oldValue) && \is_array($newValue)) {
                            // Format numbers in arrays to make comparison make sense.
                            self::formatNumbers($oldValue);
                            self::formatNumbers($newValue);
                        }

                        $isChanged = $oldValue !== $newValue;
                        switch ($type) {
              case 'float':
                // Compare numeric values with a fixed scale
                $isChanged = !self::areEqual($oldValue, $newValue);

                break;
            }

                        if ($isChanged) {
                            $changes[] = [
                                'property' => $name,
                                'type' => $type,
                                'oldValue' => $oldValue,
                                'newValue' => $newValue,
                            ];
                        }
                    }
                } catch (\Exception $ex) {
                }
            }
        }

        return $changes;
    }

    public static function getCalculationWarnings($entity, array $properties, $prefix = '', $subEntities = null)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $errors = [];

        if (!empty($properties)) {
            $propertyErrors = array_filter($properties, function ($property) use ($entity, $accessor) {
                $value = $accessor->isReadable($entity, $property) ? $accessor->getValue($entity, $property) : null;

                return null === $value;
            });

            if ($prefix) {
                $propertyErrors = array_map(function ($error) use ($prefix) {
                    return 'appbundle.'.$prefix.'.'.$error;
                }, $propertyErrors);
            }
        }
        if (!empty($propertyErrors)) {
            $errors['errors'] = $propertyErrors;
        }

        if (!empty($subEntities)) {
            foreach ($subEntities as $e) {
                if ($e->getCalculationWarnings()) {
                    $errors[$prefix][] = $e->getIndexNumber();
                }
            }
        }

        return $errors;
    }

    /**
     * Calculate Net Present Value.
     *
     * @param float $rate
     *                      The rate
     * @param array $values
     *                      The values. Indexes should be years (from 1).
     *
     * @return float
     *               The npv
     */
    public static function npv($rate, array $values)
    {
        $npv = 0;

        // @see http://stackoverflow.com/questions/2027460/how-to-calculate-npv
        foreach ($values as $year => $value) {
            $npv += $value / pow(1 + $rate, $year);
        }

        return $npv;
    }

    public static function divide($numerator, $denominator)
    {
        return 0 === $denominator ? 0 : ($numerator / $denominator);
    }

    // @see https://en.wikipedia.org/wiki/Mortgage_calculator#Monthly_payment_formula
    public static function pmt($r, $N, $P)
    {
        return -self::divide($r * $P, 1 - pow(1 + $r, -$N));
    }

    public static function fordelbesparelse($BesparKwh, $kilde, $type)
    {
        if ($kilde) {
            switch ($type) {
        case 'EL':
        case 'VARME':
        case 'PRIORITEREL':
        case 'PRIORITERVARME':
          /* Konverteringer bruges ikke – endnu …
             If Worksheets("2.Forsyning").Range("al" & y) <> "" Then 'der er beregnet konvertering
             t = Worksheets("2.Forsyning").Range("al" & y).Value 'række for konvertering indtastes

             If Worksheets("2.Forsyning").Range("a" & t) <> "" Then 'konverteringen er tilvalgt
             If typen = "EL" Or typen = "VARME" Then
             If Worksheets("2.Forsyning").Range("am" & t) = 1 Then 'konvertering prioriteres forud for øvrige tiltag
             y = t 'denne funktion bypasses hvis de er er tale om KONVERTERINGVARME eller KONVERTERINGEL, hvor det eksisterende værks værdier skal anvendes.
             'MsgBox ("y er " & y)
             End If

             ElseIf typen = "PRIORITEREL" Then
             y = t 'prioriterel og varme er altid efter nye forsyningskildes effektivitet, uanset om prioritering er 1 eller 2
             typen = "EL"
             ElseIf typen = "PRIORITERVARME" Then
             y = t
             typen = "VARME"
             'MsgBox ("prioritering virker")
             End If
             End If
             End If
          */
          break;
        case 'KONVERTERINGVARME':
          $type = 'VARME';

          break;
        case 'KONVERTERINGEL':
          $type = 'EL';

          break;
      }

            if ('EL' === $type) {
                $BesparKwh *= $kilde->getSamletEleffektivitet();
            } elseif ('VARME' === $type) {
                $BesparKwh *= $kilde->getSamletVarmeeffektivitet();
            }
        }

        if ('EL' === $type || 'VARME' === $type) {
            return $BesparKwh;
        }

        return 0;
        /*
Public Function FordelBesparelse(BesparKwh As Single, Kilde As String, typen As String)

        Dim lastRow As Integer
        Dim y As Integer
        Dim t As Integer

        With Worksheets("2.Forsyning")
            lastRow = Worksheets("2.Forsyning").Range("a3").Value 'sidste række i øverste tabel
        End With

        For y = 14 To lastRow

            If Worksheets("2.Forsyning").Range("a" & y) = Kilde Then
                If typen = "EL" Or typen = "VARME" Or typen = "PRIORITEREL" Or typen = "PRIORITERVARME" Then 'tjekker at der er tale om alm. el og varme og ikke "konverteringvarme" hvor det gamle forsyningsværks værdier skal bruges
                    If Worksheets("2.Forsyning").Range("al" & y) <> "" Then 'der er beregnet konvertering
                        t = Worksheets("2.Forsyning").Range("al" & y).Value 'række for konvertering indtastes

                        If Worksheets("2.Forsyning").Range("a" & t) <> "" Then 'konverteringen er tilvalgt
                            If typen = "EL" Or typen = "VARME" Then
                                If Worksheets("2.Forsyning").Range("am" & t) = 1 Then 'konvertering prioriteres forud for øvrige tiltag
                                    y = t 'denne funktion bypasses hvis de er er tale om KONVERTERINGVARME eller KONVERTERINGEL, hvor det eksisterende værks værdier skal anvendes.
                                    'MsgBox ("y er " & y)
                                End If

                            ElseIf typen = "PRIORITEREL" Then
                            y = t 'prioriterel og varme er altid efter nye forsyningskildes effektivitet, uanset om prioritering er 1 eller 2
                            typen = "EL"
                            ElseIf typen = "PRIORITERVARME" Then
                            y = t
                            typen = "VARME"
                            'MsgBox ("prioritering virker")
                            End If
                        End If
                    End If

                ElseIf typen = "KONVERTERINGVARME" Then 'dvs. regner altid med gamle værks effektivitet
                    typen = "VARME"
                ElseIf typen = "KONVERTERINGEL" Then 'dvs. regner altid med gamle værks effektivitet
                    typen = "EL"
                End If

                If typen = "EL" Then
                     BesparKwh = BesparKwh * Worksheets("2.Forsyning").Range("ak" & y)
                End If

                If typen = "VARME" Then
                    BesparKwh = BesparKwh * Worksheets("2.Forsyning").Range("aj" & y)
                End If

                y = lastRow 'såfremt der forsyningskilden er fundet, skal efterfølgende rækker ikke kontrolleres
            End If

        Next y

        If typen = "VARME" Then
            FordelBesparelse = BesparKwh
        End If

        If typen = "EL" Then
            FordelBesparelse = BesparKwh
        End If

End Function
*/
    }

    /**
     * Format a number.
     *
     * @param mixed $value
     */
    protected static function formatNumber($value)
    {
        $decimals = abs(log10(self::$allowedDeviance));

        return number_format($value, $decimals);
    }

    /**
     * Get calculated fields on an entity.
     *
     * @param entity $entity
     *                       The entity
     *
     * @return array
     *               List of calculated fields
     */
    protected function getCalculatedFields($entity)
    {
        $reflectionClass = new \ReflectionClass($entity);
        $properties = $reflectionClass->getProperties();

        $calculatedFieldNames = array_map(function ($property) {
            return $property->getName();
        }, array_filter($properties, function ($property) {
            return false !== strpos($property->getDocComment(), '@Calculated');
        }));

        $em = $this->container->get('doctrine')->getManager();
        $className = \get_class($entity);
        $metadata = $em->getClassMetadata($className);

        return array_filter($metadata->fieldMappings, function ($field) use ($calculatedFieldNames) {
            return \in_array($field['fieldName'], $calculatedFieldNames, true);
        });
    }

    /**
     * Format all numbers in array structure.
     */
    private static function formatNumbers(array &$array)
    {
        array_walk_recursive($array, function (&$value) {
            if (is_numeric($value)) {
                $value = self::formatNumber($value);
            }
        });
    }
}

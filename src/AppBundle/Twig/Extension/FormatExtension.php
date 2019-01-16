<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Twig\Extension;

use Twig_SimpleFilter;

/**
 * Class FormatExtension.
 *
 * Twig extension to help formatting $values (mostly numbers)
 */
class FormatExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('format_json', [$this, 'formatToJSON'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('format_hundreds', [$this, 'formatToHundreds'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('format_tens', [$this, 'formatToTens'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('format_zeros', [$this, 'formatToZeros'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('format_integer', [$this, 'formatInteger'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('format_decimal', [$this, 'formatDecimal'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('format_amount', [$this, 'formatAmount'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('format_one_decimal', [$this, 'formatOneDecimal'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('format_percent', [$this, 'formatPercent'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('format_percent_nounit', [$this, 'formatPercentNoUnit'], ['is_safe' => ['all']]),
        ];
    }

    public function formatToJSON($i)
    {
        return number_format($i, 2, ',', '');
    }

    public function formatToHundreds($i)
    {
        $rounded = $this->formatRound($i, -2);

        return number_format($rounded, 0, ',', '.');
    }

    public function formatToTens($i)
    {
        $rounded = $this->formatRound($i, -1);

        return number_format($rounded, 0, ',', '.');
    }

    public function formatToZeros($i)
    {
        $rounded = $this->formatRound($i, 0);

        return number_format($rounded, 0, ',', '.');
    }

    public function formatInteger($number)
    {
        return $this->formatDecimal($number, 0);
    }

    public function formatAmount($number, $numberOfDecimals = 0)
    {
        return $this->formatDecimal($number, $numberOfDecimals);
    }

    public function formatOneDecimal($number)
    {
        return $this->formatDecimal($number, 1);
    }

    public function formatDecimal($number, $numberOfDecimals = 2)
    {
        if (null === $number) {
            return '–';
        }
        // if number is smaller then what we can display with the given decimals
        if ($number < (1 / pow(10, $numberOfDecimals) && $number > (-1 / pow(10, $numberOfDecimals)))) {
            $number = 0;
        }
        $formatter = $this->getNumberFormatter(null, \NumberFormatter::DECIMAL);
        $formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $numberOfDecimals);
        $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $numberOfDecimals);

        return $formatter->format($number);
    }

    public function formatPercent($number, $numberOfDecimals = 2)
    {
        if (null === $number) {
            return '–';
        }
        $formatter = $this->getNumberFormatter(null, \NumberFormatter::PERCENT);
        $formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $numberOfDecimals);
        $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $numberOfDecimals);

        return $formatter->format($number);
    }

    public function formatPercentNoUnit($number, $numberOfDecimals = 2)
    {
        return $this->formatDecimal(100 * $number, $numberOfDecimals);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'format_extension';
    }

    private function formatRound($value, $precision)
    {
        if ($precision < -4 || $precision > 15) {
            throw new \Exception($precision.' - Must be and integer between -4 and 15');
        }

        $value = (int) $value;

        if ($precision >= 0) {
            $rounded = round($value, $precision);
        } else {
            $precision = (int) (pow(10, abs($precision)));
            $value = $value >= 0 ? $value + (5 * $precision / 10) : $value - (5 * $precision / 10);
            $rounded = round($value - ($value % $precision), 0);
        }

        // +0 to avoid "-0" output
        return $rounded + 0;
    }

    private function getNumberFormatter($locale, $style)
    {
        static $formatter, $currentStyle;

        $locale = null !== $locale ? $locale : \Locale::getDefault();

        if ($formatter && $formatter->getLocale() === $locale && $currentStyle === $style) {
            // Return same instance of NumberFormatter if parameters are the same
            // to those in previous call
            return $formatter;
        }

        $formatter = \NumberFormatter::create($locale, $style);

        return $formatter;
    }
}

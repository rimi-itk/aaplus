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
 * Class TiltagTypeExtension.
 *
 * Twig extension to assist in polymorphic template rendering
 */
class FilterExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('b2icon', [$this, 'booleanToIconFilter'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('b2number', [$this, 'booleanToNumberFilter'], ['is_safe' => ['all']]),
            new Twig_SimpleFilter('b2class', [$this, 'booleanToClassFilter'], ['is_safe' => ['all']]),
        ];
    }

    public function booleanToIconFilter($boolean)
    {
        if (null === $boolean) {
            return '<span class="fa fa-circle-thin"></span>';
        }

        return $boolean ? '<span class="fa fa-check"></span>' : '<span class="fa fa-minus"></span>';
    }

    public function booleanToNumberFilter($boolean)
    {
        if (null === $boolean) {
            return '';
        }

        return $boolean ? '1' : '0';
    }

    public function booleanToClassFilter($boolean)
    {
        if (null === $boolean) {
            return '';
        }

        return $boolean ? 'selected' : 'not-selected';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'filter_extension';
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Twig\Extension;

use AppBundle\Entity\Tiltag;
use Twig_SimpleFunction;

/**
 * Class TiltagTypeExtension.
 *
 * Twig extension to assist in polymorphic template rendering
 */
class TiltagTypeExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('tiltag_type', [$this, 'getTiltagType'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('tiltag_route', [$this, 'getTiltagRouteName'], ['is_safe' => ['html']]),
            new Twig_SimpleFunction('is_missing_tiltag_type', [$this, 'isMissingTiltagType'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get a string representation of the type of 'tiltag'.
     *
     * @param $object
     *   The instance to get the type of. Must be Tiltag or descendant of Tiltag
     *
     * @return string
     *                String representation of the type
     */
    public function getTiltagType(Tiltag $object)
    {
        if (preg_match('/\\\\(?<type>[^\\\\]+)Tiltag$/', \get_class($object), $matches)) {
            return $matches['type'];
        }

        throw new \InvalidArgumentException('Cannot get type of non-Tiltag object ('.\get_class($object).')');
    }

    /**
     * Get the route name for the type of Tiltag / Action.
     *
     * @param $object
     *   The instance to get the type of. Must be Tiltag or descendant of Tiltag
     * @param $action
     *   String representation of the action
     *
     * @return string
     *                The name of the route
     */
    public function getTiltagRouteName(Tiltag $object, $action)
    {
        return $this->getTiltagType($object).'_'.$action;
    }

    /**
     * @TODO: Missing description.
     *
     * @param $tiltag
     *   Array of Tiltag to check for the type in. Must be Tiltag or descendant of Tiltag
     * @param $type
     *   String representation of the type to check for
     *
     * @return bool
     * @TODO: Missing description.
     */
    public function isMissingTiltagType($tiltag, $type)
    {
        foreach ($tiltag as $t) {
            if ($this->getTiltagType($t) === $type) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'tiltag_type_extension';
    }
}

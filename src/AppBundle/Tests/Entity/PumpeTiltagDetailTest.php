<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Pumpe;

/**
 * @coversNothing
 */
class PumpeTiltagDetailTest extends TiltagDetailTestCase
{
    public function loadProperties(array $properties)
    {
        $properties['pumpe'] = $this->getPumpe($properties['pumpe']);

        return $properties;
    }

    private function getPumpe(array $properties = null)
    {
        return $properties ? $this->setProperties(new Pumpe(), $properties) : null;
    }
}

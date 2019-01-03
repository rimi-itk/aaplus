<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Solcelle;

/**
 * @coversNothing
 */
class SolcelleTiltagDetailTest extends TiltagDetailTestCase
{
    public function loadProperties(array $properties)
    {
        $properties['solcelle'] = $this->getSolcelle($properties['solcelle']);

        return $properties;
    }

    private function getSolcelle(array $properties = null)
    {
        return $properties ? $this->setProperties(new Solcelle(), $properties) : null;
    }
}

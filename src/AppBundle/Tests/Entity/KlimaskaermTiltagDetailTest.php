<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Klimaskaerm;

/**
 * @coversNothing
 */
class KlimaskaermTiltagDetailTest extends TiltagDetailTestCase
{
    public function loadProperties(array $properties)
    {
        $properties['klimaskaerm'] = $this->getKlimaskaerm($properties['klimaskaerm']);

        return $properties;
    }

    private function getKlimaskaerm(array $properties = null)
    {
        return $properties ? $this->setProperties(new Klimaskaerm(), $properties) : null;
    }
}

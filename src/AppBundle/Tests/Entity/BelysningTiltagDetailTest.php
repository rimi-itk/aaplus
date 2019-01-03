<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\BelysningTiltagDetail\Lyskilde;

/**
 * @coversNothing
 */
class BelysningTiltagDetailTest extends TiltagDetailTestCase
{
    public function loadProperties(array $properties)
    {
        $properties['lyskilde'] = $this->getLyskilde($properties['lyskilde']);
        $properties['nyLyskilde'] = $this->getLyskilde($properties['nyLyskilde']);

        return $properties;
    }

    private function getLyskilde(array $properties = null)
    {
        return $properties ? $this->setProperties(new Lyskilde(), $properties) : null;
    }
}

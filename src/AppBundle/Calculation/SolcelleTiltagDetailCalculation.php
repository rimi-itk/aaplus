<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Calculation;

use AppBundle\Entity\SolcelleTiltagDetail;
use AppBundle\Entity\TiltagDetail;

class SolcelleTiltagDetailCalculation extends TiltagDetailCalculation
{
    /**
     * Calculate SolcelleTiltagDetail(s).
     *
     * @param tiltagDetail $detail
     *                             The entity to calculate
     *
     * @return tiltagDetail
     *                      The calculated entity
     */
    public function calculate(TiltagDetail $detail)
    {
        if ($detail instanceof SolcelleTiltagDetail) {
            $solcelle = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Solcelle')->findByKWp($detail->getAnlaegsstoerrelseKWp());
            $detail->setSolcelle($solcelle);
            $detail->calculate();
        }

        return $detail;
    }
}

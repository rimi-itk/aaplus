<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Calculation;

use AppBundle\Entity\Rapport;
use Symfony\Component\DependencyInjection\Container;

class RapportCalculation extends Calculation
{
    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /*
     * Calculate rapport by dispatching to appropriate calculation service.
     *
     * Note: The passed is modified.
     *
     * @param Rapport $rapport
     *   The rapport.
     *
     * @return Rapport
     *   The rapport.
     */
    public function calculate(Rapport $rapport)
    {
        $traepillefyr = $this->container->get('doctrine')->getManager()->getRepository('AppBundle:Forsyningsvaerk')->findOneByNavn('Træpillefyr');
        $rapport->setTraepillefyr($traepillefyr);
        $rapport->calculate();

        return $rapport;
    }

    public function getChanges($entity)
    {
        $changes = [];

        if ($entity instanceof Rapport) {
            $tiltagCalculation = $this->container->get('aaplus.tiltag_calculation');
            foreach ($entity->getTilvalgteTiltag() as $tiltag) {
                $tiltagChanges = $tiltagCalculation->getChanges($tiltag);
                if ($tiltagChanges) {
                    $changes['tiltag:'.$tiltag->getId()] = [
                        'property' => $tiltag->getIndexNumber().'. '.$tiltag->getTitle(),
                        'type' => 'tiltag',
                        'entity' => $tiltag,
                        'changes' => $tiltagChanges,
                    ];
                    // We need to calculate the Tiltag for use when calculation the Rapport.
                    $tiltag->calculate();
                }
            }
        }

        $changes += parent::getChanges($entity);

        return $changes;
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Calculation;

use AppBundle\Entity\Baseline;
use AppBundle\Entity\BaselineKorrektion;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\Container;

class BaselineKorrektionCalculation extends Calculation
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Calculate baseline before an update.
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof BaselineKorrektion) {
            return;
        }

        $this->calculate($entity->getBaseline());
    }

    /**
     * Calculate baseline before a persist.
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->preUpdate($args);
    }

    public function calculate(Baseline $baseline)
    {
        $GDNormalAar = null;
        $normtal = $this->container->get('doctrine')->getRepository('AppBundle:GraddageFordeling')->findOneByTitel('Normtal');
        if ($normtal) {
            $GDNormalAar = $normtal->getSumAar();
        }

        $baseline->calculate($GDNormalAar);

        return $baseline;
    }
}

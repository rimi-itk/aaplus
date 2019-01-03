<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Listener;

use AppBundle\Entity\Rapport;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

class RapportListener
{
    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Rapport) {
            $traepillefyr = $args->getObjectManager()->getRepository('AppBundle:Forsyningsvaerk')->findOneByNavn('Træpillefyr');
            $entity->setTraepillefyr($traepillefyr);
            $olie = $args->getObjectManager()->getRepository('AppBundle:Forsyningsvaerk')->findOneByNavn('Olie');
            $entity->setOlie($olie);
        }
    }
}

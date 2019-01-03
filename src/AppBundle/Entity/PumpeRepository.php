<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PumpeRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PumpeRepository extends EntityRepository
{
    /**
     * Check if entity can be removed (deleted). If not, return an error message.
     *
     * @return null|string
     */
    public function getRemoveErrorMessage(Pumpe $pumpe)
    {
        $query = $this->_em->createQuery('SELECT d FROM AppBundle:PumpeTiltagDetail d WHERE d.pumpe = :pumpe');
        $query->setParameter('pumpe', $pumpe);
        $result = $query->getResult();

        if ($result) {
            return 'pumpe.error.in_use';
        }

        return null;
    }
}

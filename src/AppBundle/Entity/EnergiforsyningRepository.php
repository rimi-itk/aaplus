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
 * EnergiforsyningRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EnergiforsyningRepository extends EntityRepository
{
    /**
     * Check if entity can be removed (deleted). If not, return an error message.
     *
     * @return null|string
     */
    public function getRemoveErrorMessage(Energiforsyning $energiforsyning)
    {
        $query = $this->_em->createQuery('SELECT t FROM AppBundle:Tiltag t WHERE t.forsyningVarme = :energiforsyning OR t.forsyningEl = :energiforsyning');
        $query->setParameter('energiforsyning', $energiforsyning);
        $result = $query->getResult();

        if ($result) {
            return 'energiforsyninger.error.in_use';
        }

        return null;
    }
}
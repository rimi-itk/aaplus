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
 * ForsyningsvaerkRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ForsyningsvaerkRepository extends EntityRepository
{
    /**
     * Check if entity can be removed (deleted). If not, return an error message.
     *
     * @return null|string
     */
    public function getRemoveErrorMessage(Forsyningsvaerk $forsyningsvaerk)
    {
        $query = $this->_em->createQuery('SELECT b FROM AppBundle:Bygning b WHERE b.forsyningsvaerkVand = :forsyningvaerk OR b.forsyningsvaerkVarme = :forsyningvaerk OR b.forsyningsvaerkEl = :forsyningvaerk');
        $query->setParameter('forsyningvaerk', $forsyningsvaerk);
        $result = $query->getResult();

        if ($result) {
            return 'forsyningsvaerk.error.in_use';
        }

        return null;
    }
}

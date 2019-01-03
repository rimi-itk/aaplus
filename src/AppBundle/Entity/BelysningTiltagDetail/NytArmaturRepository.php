<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity\BelysningTiltagDetail;

use Doctrine\ORM\EntityRepository;

/**
 * NytArmaturRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NytArmaturRepository extends EntityRepository
{
    /**
     * Check if entity can be removed (deleted). If not, return an error message.
     *
     * @return null|string
     */
    public function getRemoveErrorMessage(NytArmatur $nytArmatur)
    {
        $query = $this->_em->createQuery('SELECT d FROM AppBundle:BelysningTiltagDetail d WHERE d.nytArmatur = :nytArmatur');
        $query->setParameter('nytArmatur', $nytArmatur);
        $result = $query->getResult();

        if ($result) {
            return 'nytArmatur.error.in_use';
        }

        return null;
    }
}

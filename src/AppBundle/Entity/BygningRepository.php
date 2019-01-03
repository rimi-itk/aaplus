<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\BygningStatusType;

/**
 * BygningRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BygningRepository extends BaseRepository
{
    /**
     * Find all Bygning that a User has access to.
     *
     * @param User  $user
     * @param bool  $returnQuery
     * @param mixed $onlyOwnBuildings
     *
     * @return array|\Doctrine\ORM\Query
     */
    public function findByUser(User $user, $returnQuery = false, $onlyOwnBuildings = false)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('b')->from('AppBundle:Bygning', 'b');
        $qb->orderBy('b.updatedAt', 'DESC');

        $this->limitQueryToUserAccess($user, $qb, $onlyOwnBuildings);

        $query = $qb->getQuery();

        return $returnQuery ? $query : $query->getResult();
    }

    /**
     * Find all Bygning from given segment.
     *
     * @param Segment $segment
     * @param bool    $returnQuery
     *
     * @return array|\Doctrine\ORM\Query
     */
    public function findBySegment(Segment $segment, $returnQuery = false)
    {
        $query = $this->_em->createQuery('SELECT b FROM AppBundle:Bygning b WHERE :segment = b.segment');
        $query->setParameter('segment', $segment);

        return $returnQuery ? $query : $query->getResult();
    }

    /**
     * Search all Bygning that a User has access to.
     *
     * @param User  $user
     * @param bool  $returnQuery
     * @param mixed $search
     *
     * @return array|\Doctrine\ORM\Query
     */
    public function searchByUser(User $user, $search)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('b', 's')
      ->from('AppBundle:Bygning', 'b')
      ->leftJoin('b.segment', 's');

        if (!empty($search['navn'])) {
            $qb->andWhere('b.navn LIKE :navn')
        ->setParameter('navn', '%'.$search['navn'].'%');
        }

        if (!empty($search['adresse'])) {
            $qb->andWhere('b.adresse LIKE :adresse')
        ->setParameter('adresse', '%'.$search['adresse'].'%');
        }

        if (!empty($search['postBy'])) {
            $qb->andWhere('b.postBy LIKE :postBy')
        ->setParameter('postBy', '%'.$search['postBy'].'%');
        }

        if (!empty($search['bygId'])) {
            $qb->andWhere('b.bygId = :bygId')
        ->setParameter('bygId', $search['bygId']);
        }

        if (!empty($search['postnummer'])) {
            $qb->andWhere('b.postnummer = :postnummer')
        ->setParameter('postnummer', $search['postnummer']);
        }

        if (!empty($search['status'])) {
            $qb->andWhere('b.status = :status')
        ->setParameter('status', $search['status']);
        }

        if (!empty($search['segment'])) {
            $qb->andWhere('b.segment = :segment')
        ->setParameter('segment', $search['segment']);
        }

        $this->limitQueryToUserAccess($user, $qb);

        $qb->addOrderBy('b.navn');

        return $qb->getQuery();
    }

    /**
     * Field sum.
     *
     * @param User  $user
     * @param mixed $field
     * @param mixed $search
     *
     * @return array|\Doctrine\ORM\Query
     */
    public function getFieldSum(User $user, $field, $search)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('sum(r.'.$field.')')
      ->from('AppBundle:Bygning', 'b')
      ->leftJoin('b.rapport', 'r')
      ->leftJoin('b.segment', 's');

        if (!empty($search['segment'])) {
            $qb->andWhere('b.segment = :segment')
        ->setParameter('segment', $search['segment']);
        }

        if (!empty($search['forkortelse'])) {
            $qb->andWhere('s.forkortelse = :forkortelse')
        ->setParameter('forkortelse', $search['forkortelse']);
        }

        if (!empty($search['type'])) {
            $qb->andWhere('b.type = :type')
        ->setParameter('type', $search['type']);
        }

        if (!empty($search['year'])) {
            $qb->andWhere('YEAR(r.datering) = :year')
        ->setParameter('year', $search['year']);
        }

        $this->limitQueryToUserAccess($user, $qb);

        return $qb->getQuery();
    }

    /**
     * Field avg diff.
     *
     * @param User  $user
     * @param mixed $field
     * @param mixed $baseline
     * @param mixed $search
     *
     * @return array|\Doctrine\ORM\Query
     */
    public function getFieldAvgDiff(User $user, $field, $baseline, $search)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('(sum(r.'.$field.') / sum(r.'.$baseline.')) * (-100)')
      ->where('r.'.$field.' IS NOT NULL')
      ->andWhere('r.'.$baseline.' IS NOT NULL')
      ->andWhere('r.'.$baseline.' != 0')
      ->from('AppBundle:Bygning', 'b')
      ->leftJoin('b.rapport', 'r')
      ->leftJoin('b.segment', 's');

        if (!empty($search['segment'])) {
            $qb->andWhere('b.segment = :segment')
        ->setParameter('segment', $search['segment']);
        }

        if (!empty($search['forkortelse'])) {
            $qb->andWhere('s.forkortelse = :forkortelse')
        ->setParameter('forkortelse', $search['forkortelse']);
        }

        if (!empty($search['type'])) {
            $qb->andWhere('b.type = :type')
        ->setParameter('type', $search['type']);
        }

        if (!empty($search['year'])) {
            $qb->andWhere('YEAR(r.datering) = :year')
        ->setParameter('year', $search['year']);
        }

        $this->limitQueryToUserAccess($user, $qb);

        return $qb->getQuery();
    }

    /**
     * Search for buildings with specific status and user.
     *
     * @param \AppBundle\Entity\User                  $user
     * @param \AppBundle\DBAL\Types\BygningStatusType $status
     *
     * @return \Doctrine\ORM\Query
     */
    public function getByUserAndStatus(User $user, BygningStatusType $status)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('b')->from('AppBundle:Bygning', 'b');

        $qb->where('b.status = :status')->setParameter('status', $status);
        $qb->orderBy('b.updatedAt', 'DESC');

        $this->limitQueryToUserAccess($user, $qb);

        return $qb->getQuery();
    }

    /**
     * @param \AppBundle\Entity\User                  $user
     * @param \AppBundle\DBAL\Types\BygningStatusType $status
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @return mixed
     */
    public function getSummaryByUserAndStatus(User $user, BygningStatusType $status)
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('SUM(b.bruttoetageareal AS totalareal')
      ->from('AppBundle:Bygning', 'b');

        $qb->where('b.status = :status')->setParameter('status', $status);

        $this->limitQueryToUserAccess($user, $qb);

        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Get all building types.
     *
     * @return array
     */
    public function getBuildingTypes()
    {
        $qb = $this->_em->createQueryBuilder();

        $qb->select('b.type')
      ->distinct(true)
      ->from('AppBundle:Bygning', 'b');

        return $qb->getQuery()->getResult();
    }

    public function getRemoveErrorMessage(Bygning $bygning)
    {
        $query = $this->_em->createQuery('SELECT r FROM AppBundle:Rapport r WHERE r.bygning = :bygning');
        $query->setParameter('bygning', $bygning);
        $result = $query->getResult();

        if ($result) {
            return 'bygning.error.in_use';
        }

        return null;
    }
}

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
 * BaselineRepository.
 */
class BaselineRepository extends BaseRepository
{
    /**
     * Check if a User has edit rights to a BAseline.
     *
     * @param User    $user
     * @param Rapport $baseline
     *
     * @return bool
     */
    public function canEdit(User $user, Baseline $baseline)
    {
        if (BygningStatusType::TILKNYTTET_RAADGIVER === $baseline->getBygning()->getStatus()) {
            return $baseline->getBygning()->getEnergiRaadgiver() === $user;
        }

        return $this->hasFullAccess($user);
    }
}

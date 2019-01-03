<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\EntityAudit;

use Doctrine\ORM\EntityManager;
use SimpleThings\EntityAudit\AuditManager as BaseAuditManager;

class AuditManager extends BaseAuditManager
{
    public function createAuditReader(EntityManager $em)
    {
        return new AuditReader($em, $this->getConfiguration(), $this->getMetadataFactory());
    }
}

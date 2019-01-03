<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Group;
use Ddeboer\DataImport\Writer\CallbackWriter;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadGroupData.
 */
class LoadGroupsData extends LoadData
{
    protected $order = 11;
    protected $flush = true;

    protected function createWriter(ObjectManager $manager)
    {
        return new CallbackWriter(function ($item) use ($manager) {
            $group = new Group($item['name']);
            $group->setRoles($item['roles'] ? explode(',', $item['roles']) : []);
            $manager->persist($group);
        });
    }
}

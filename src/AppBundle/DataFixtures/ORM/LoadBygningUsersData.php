<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DataFixtures\ORM;

use Ddeboer\DataImport\Writer\CallbackWriter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUserData.
 */
class LoadBygningUsersData extends LoadData
{
    protected $order = 21;
    protected $flush = true;

    protected function createWriter(ObjectManager $manager)
    {
        return new CallbackWriter(function ($item) use ($manager) {
            $bygning = $manager->getRepository('AppBundle:Bygning')->findOneByEnhedsys($item['bygning_enhedsys']);
            if (!$bygning) {
                $this->writeError('No such Bygning: '.$item['bygning_enhedsys']);
            } else {
                $users = $manager->getRepository('AppBundle:User')->findBy(['id' => explode(',', $item['user_ids'])]);
                $bygning->setUsers(new ArrayCollection($users));
            }
        });
    }
}

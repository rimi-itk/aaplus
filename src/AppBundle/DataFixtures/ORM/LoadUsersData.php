<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Ddeboer\DataImport\Writer\CallbackWriter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUserData.
 */
class LoadUsersData extends LoadData
{
    protected $order = 12;
    protected $flush = true;

    protected function createWriter(ObjectManager $manager)
    {
        return new CallbackWriter(function ($item) use ($manager) {
            $user = new User();

            $groups = new ArrayCollection();

            if ($item['groups']) {
                $ids = explode(',', $item['groups']);
                $repository = $manager->getRepository('AppBundle:Group');
                $groups = new ArrayCollection($repository->findBy(['id' => $ids]));
            }

            $encoder = $this->container->get('security.password_encoder');
            $user->setUsername($item['username'])
                ->setPassword($encoder->encodePassword($user, $item['password']))
                ->setEmail($item['email'])
                ->setFirstname($item['firstname'])
                ->setLastname($item['lastname'])
                ->setPhone($item['phone'])
                ->setRoles($item['roles'] ? explode(',', $item['roles']) : [])
                ->setGroups($groups)
                ->setEnabled(true);
            $manager->persist($user);
        });
    }
}

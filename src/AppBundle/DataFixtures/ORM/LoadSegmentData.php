<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Segment;
use Ddeboer\DataImport\Writer\CallbackWriter;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadUserData.
 */
class LoadSegmentData extends LoadData
{
    protected $order = 14;
    protected $flush = true;

    protected function createWriter(ObjectManager $manager)
    {
        return new CallbackWriter(function ($item) use ($manager) {
            $segment = new Segment();

            $segment->setNavn($item['navn'])
        ->setMagistrat($item['magistrat'])
        ->setForkortelse($item['forkortelse']);
            $manager->persist($segment);
        });
    }
}

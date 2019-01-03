<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\PumpeTiltagDetailApplikation;
use Ddeboer\DataImport\Writer\CallbackWriter;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadPumpeTiltagDetailApplikationData.
 */
class LoadPumpeTiltagDetailApplikationData extends LoadData
{
    protected $order = 2;
    protected $flush = true;

    protected function createWriter(ObjectManager $manager)
    {
        return new CallbackWriter(function ($item) use ($manager) {
            $applikation = new PumpeTiltagDetailApplikation();

            $applikation->setNavn($item['navn']);
            $manager->persist($applikation);
        });
    }
}

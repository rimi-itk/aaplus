<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\TiltagsKategori;
use Ddeboer\DataImport\Writer\CallbackWriter;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadTiltagsKategoriData.
 */
class LoadTiltagsKategoriData extends LoadData
{
    protected $order = 3;
    protected $flush = true;

    protected function createWriter(ObjectManager $manager)
    {
        return new CallbackWriter(function ($item) use ($manager) {
            $kategori = new TiltagsKategori();

            $kategori->setNavn($item['navn']);
            $manager->persist($kategori);
        });
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use AppBundle\DBAL\Types\PrimaerEnterpriseType;
use Doctrine\ORM\EntityRepository;

/**
 * TiltagRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TiltagRepository extends EntityRepository
{
    /**
     * Create a new Tiltag based on type.
     *
     * @param string $type
     *
     * @return Tiltag
     */
    public function create($type)
    {
        $type = ucwords($type);
        $className = '\\AppBundle\\Entity\\'.$type.'Tiltag';

        if (!class_exists($className)) {
            throw new \InvalidArgumentException('Unknown tiltag type: '.$type);
        }

        $tiltag = new $className();

        switch ($type) {
      case 'Solcelle':
        $tiltag->setPrimaerEnterprise(PrimaerEnterpriseType::VE);
        $tiltag->setTiltagskategori($this->getTiltagskategoriByName('Solceller'));

        break;
      case 'Tekniskisolering':
        $tiltag->setPrimaerEnterprise(PrimaerEnterpriseType::VVS);
        $tiltag->setTiltagskategori($this->getTiltagskategoriByName('Varmeanlæg - generelt'));

        break;
      case 'Belysning':
        $tiltag->setPrimaerEnterprise(PrimaerEnterpriseType::EL);
        $tiltag->setTiltagskategori($this->getTiltagskategoriByName('Belysning'));

        break;
      case 'Klimaskaerm':
        $tiltag->setPrimaerEnterprise(PrimaerEnterpriseType::TOEMRER_ISOLATOER);
        $tiltag->setTiltagskategori($this->getTiltagskategoriByName('Klimaskærm'));

        break;
      case 'Pumpe':
        $tiltag->setPrimaerEnterprise(PrimaerEnterpriseType::VVS);
        $tiltag->setTiltagskategori($this->getTiltagskategoriByName('Pumper'));

        break;
      case 'Vindue':
        $tiltag->setPrimaerEnterprise(PrimaerEnterpriseType::TOEMRER);
        $tiltag->setTiltagskategori($this->getTiltagskategoriByName('Vinduer, ovenlys, døre'));

        break;
    }

        return $tiltag;
    }

    private function getTiltagskategoriByName($name)
    {
        return $this->_em->getRepository('AppBundle:Tiltagskategori')->findOneBy(['navn' => $name]);
    }
}

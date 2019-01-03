<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Uploadable\Mapping\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SpecialTiltagDetail.
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @Gedmo\Uploadable(
 *   path="uploads/specialtiltag/",
 *   filenameGenerator=Validator::FILENAME_GENERATOR_ALPHANUMERIC,
 *   allowOverwrite=false,
 *   appendNumber=true
 * )
 */
class SpecialTiltagDetail extends TiltagDetail
{
    /**
     * @var string
     *
     * @ORM\Column(name="Kommentar", type="text", nullable=true)
     */
    protected $kommentar;

    /**
     * @ORM\Column(name="filepath", type="string", nullable=true)
     * @Gedmo\UploadableFilePath
     * @Assert\File()
     */
    protected $filepath;

    public function setKommentar($kommentar)
    {
        $this->kommentar = $kommentar;
    }

    public function getKommentar()
    {
        return $this->kommentar;
    }

    /**
     * Sets filepath.
     *
     * @param UploadedFile $filepath
     */
    public function setFilepath(UploadedFile $filepath = null)
    {
        $this->filepath = $filepath;
    }

    /**
     * Get filepath.
     *
     * @return UploadedFile
     */
    public function getFilepath()
    {
        return $this->filepath;
    }

    /**
     * Get filename.
     *
     * @return string
     */
    public function getFilename()
    {
        return basename($this->filepath);
    }

    public function getAllFiles()
    {
        return $this->getFilepath() ? $this->getFilepath() : null;
    }

    public function handleUploads($manager)
    {
        $fileInfo = $this->getFilepath();
        if (\is_object($fileInfo) && $fileInfo instanceof UploadedFile) {
            $manager->markEntityToUpload($this, $fileInfo);
        }
    }
}

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

/**
 * TiltagsKategori.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TiltagsKategori
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="navn", type="string", length=255)
     */
    private $navn;

    /**
     * toString.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getNavn();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set navn.
     *
     * @param string $navn
     *
     * @return TiltagsKategori
     */
    public function setNavn($navn)
    {
        $this->navn = $navn;

        return $this;
    }

    /**
     * Get navn.
     *
     * @return string
     */
    public function getNavn()
    {
        return $this->navn;
    }
}

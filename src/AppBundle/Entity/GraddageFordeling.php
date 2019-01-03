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
 * GraddageFordeling.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\GraddageFordelingRepository")
 */
class GraddageFordeling extends AarsFordeling
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titel", type="string", length=255, nullable=true)
     */
    protected $titel;

    /**
     * Constructor.
     *
     * @param null|mixed $titel
     * @param null|mixed $januar
     * @param null|mixed $februar
     * @param null|mixed $marts
     * @param null|mixed $april
     * @param null|mixed $maj
     * @param null|mixed $juni
     * @param null|mixed $juli
     * @param null|mixed $august
     * @param null|mixed $september
     * @param null|mixed $oktober
     * @param null|mixed $november
     * @param null|mixed $december
     */
    public function __construct(
      $titel = null,
      $januar = null,
      $februar = null,
      $marts = null,
      $april = null,
      $maj = null,
      $juni = null,
                              $juli = null,
      $august = null,
      $september = null,
      $oktober = null,
      $november = null,
      $december = null
  ) {
        parent::__construct($januar, $februar, $marts, $april, $maj, $juni, $juli, $august, $september, $oktober, $november, $december);
        $this->titel = $titel;
    }

    public function __toString()
    {
        return $this->titel;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * @param string $titel
     */
    public function setTitel($titel)
    {
        if ('Normtal' !== $this->titel) {
            $this->titel = $titel;
        }
    }

    public function isNormAar()
    {
        return 'Normtal' === $this->titel;
    }

    /**
     * Get the sum of the year.
     *
     * @return float
     */
    public function getSumAar()
    {
        return
      $this->januar + $this->februar + $this->marts +
      $this->april + $this->maj + $this->juni +
      $this->juli + $this->august + $this->september +
      $this->oktober + $this->november + $this->december;
    }
}

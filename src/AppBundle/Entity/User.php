<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser {
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\ManyToMany(targetEntity="Group")
   * @ORM\JoinTable(name="fos_user_group",
   *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
   *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
   * )
   */
  protected $groups;

  public function setGroups(ArrayCollection $groups) {
    $this->groups = $groups;

    return $this;
  }

  /**
   * @ORM\ManyToMany(targetEntity="Bygning", mappedBy="users")
   */
  protected $bygninger;

  public function setBygninger($bygninger) {
    $this->bygninger = $bygninger;

    return $this;
  }

  public function getBygninger() {
    return $this->bygninger;
  }

  public function __construct() {
    parent::__construct();
    $this->groups = new ArrayCollection();
    $this->bygninger = new ArrayCollection();
  }
}

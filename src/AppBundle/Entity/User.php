<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OrderBy;
use FOS\UserBundle\Model\User as BaseUser;
use JMS\Serializer\Annotation as JMS;
use Rollerworks\Component\PasswordStrength\Validator\Constraints as RollerworksPassword;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     *
     * @RollerworksPassword\PasswordStrength(minLength=8, minStrength=3, message="Vælg et stærkere kodeord")
     * @RollerworksPassword\PasswordRequirements(
     *  requireLetters=true,
     *  requireNumbers=true,
     *  requireCaseDiff=true,
     *  tooShortMessage="Kodeord skal være på mindst 8 tegn",
     *  requireCaseDiffMessage="Kodeordet skal indeholde både store og små bogstaver",
     *  missingNumbersMessage="Kodeordet skal indeholde tal",
     *  missingSpecialCharacterMessage="Kodeordet skal indeholde bogstaver")
     */
    protected $plainPassword;

    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="fos_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @OneToMany(targetEntity="Segment", mappedBy="segmentAnsvarlig")
     **/
    protected $segmenter;

    /**
     * @OneToMany(targetEntity="Bygning", mappedBy="aaplusAnsvarlig")
     * @OrderBy({"navn" = "ASC"})
     * @JMS\Exclude
     **/
    protected $ansvarlig;

    /**
     * @OneToMany(targetEntity="Bygning", mappedBy="energiRaadgiver")
     * @OrderBy({"navn" = "ASC"})
     * @JMS\Exclude
     **/
    protected $energiRaadgiver;

    /**
     * @OneToMany(targetEntity="Bygning", mappedBy="projektleder")
     * @OrderBy({"navn" = "ASC"})
     * @JMS\Exclude
     **/
    protected $projektleder;

    /**
     * @OneToMany(targetEntity="Bygning", mappedBy="projekterende")
     * @OrderBy({"navn" = "ASC"})
     * @JMS\Exclude
     **/
    protected $projekterende;

    /**
     * @ORM\ManyToMany(targetEntity="Bygning", mappedBy="users")
     */
    protected $bygninger;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=20, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=30, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=10, nullable=true)
     */
    private $phone;

    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->bygninger = new ArrayCollection();
        $this->segmenter = new ArrayCollection();
        $this->username = 'username';
    }

    /**
     * Get Name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getFirstname().' '.$this->getLastname();
    }

    /**
     * Get firstname.
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set firstname.
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get lastname.
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set lastname.
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function setGroups($groups)
    {
        if (is_a($groups, 'Doctrine\Common\Collections\ArrayCollection')) {
            $this->groups = $groups;
        } else {
            $this->groups->add($groups);
        }

        return $this;
    }

    public function getGroup()
    {
        return implode(', ', $this->getGroupNames());
    }

    public function getBygninger()
    {
        return $this->bygninger;
    }

    /**
     * Set bygninger.
     */
    public function setBygninger(\Doctrine\Common\Collections\Collection $bygninger)
    {
        $this->bygninger = $bygninger;

        return $this;
    }

    /**
     * Add bygninger.
     *
     * @param \AppBundle\Entity\Bygning $bygninger
     *
     * @return User
     */
    public function addBygninger(\AppBundle\Entity\Bygning $bygning)
    {
        $this->bygninger[] = $bygning;
        $bygning->addUser($this);

        return $this;
    }

    /**
     * Remove bygninger.
     *
     * @param \AppBundle\Entity\Bygning $bygninger
     */
    public function removeBygninger(\AppBundle\Entity\Bygning $bygning)
    {
        $this->bygninger->removeElement($bygning);
        $bygning->removeUser($this);
    }

    /**
     * Get phone.
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set phone.
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Add segmenter.
     *
     * @param \AppBundle\Entity\Segment $segmenter
     *
     * @return User
     */
    public function addSegmenter(\AppBundle\Entity\Segment $segment)
    {
        $this->segmenter[] = $segment;
        $segment->setSegmentAnsvarlig($this);

        return $this;
    }

    /**
     * Remove segmenter.
     *
     * @param \AppBundle\Entity\Segment $segmenter
     */
    public function removeSegmenter(\AppBundle\Entity\Segment $segment)
    {
        $this->segmenter->removeElement($segment);
        $segment->removeSegmentAnsvarlig();
    }

    /**
     * Get segmenter.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSegmenter()
    {
        return $this->segmenter;
    }

    /**
     * Sets the email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->setUsername($email);

        return parent::setEmail($email);
    }

    /**
     * Set the canonical email.
     *
     * @param string $emailCanonical
     *
     * @return User
     */
    public function setEmailCanonical($emailCanonical)
    {
        $this->setUsernameCanonical($emailCanonical);

        return parent::setEmailCanonical($emailCanonical);
    }
}

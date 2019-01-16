<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\RapportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TiltagVoter implements VoterInterface
{
    const VIEW = 'TILTAG_VIEW';
    const EDIT = 'TILTAG_EDIT';
    const CREATE = 'TILTAG_CREATE';
    /** @var RapportRepository $rapportRepository */
    private $rapportRepository;

    /** @var RoleHierarchyInterface $roleHierarchy */
    private $roleHierarchy;

    public function __construct(EntityManagerInterface $em, RoleHierarchyInterface $roleHierarchy)
    {
        $this->rapportRepository = $em->getRepository('AppBundle:Rapport');
        $this->roleHierarchy = $roleHierarchy;
    }

    public function supportsAttribute($attribute)
    {
        return \in_array($attribute, [
            self::VIEW,
            self::EDIT,
            self::CREATE,
        ], true);
    }

    public function supportsClass($class)
    {
        $supportedClass = 'AppBundle\Entity\Tiltag';

        return $supportedClass === $class || is_subclass_of($class, $supportedClass);
    }

    public function vote(TokenInterface $token, $tiltag, array $attributes)
    {
        // check if class of this object is supported by this voter
        if (null === $tiltag || !$this->supportsClass(\get_class($tiltag))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $rapport = $tiltag ? $tiltag->getRapport() : null;

        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== \count($attributes)) {
            throw new \InvalidArgumentException(
        'Only one attribute is allowed for CREATE, VIEW or EDIT'
      );
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (!$rapport) {
            return VoterInterface::ACCESS_DENIED;
        }

        switch ($attribute) {
      case self::VIEW:
        if ($this->hasRole($token, 'ROLE_TILTAG_VIEW') && $this->rapportRepository->hasAccess($user, $rapport->getBygning())) {
            return VoterInterface::ACCESS_GRANTED;
        }

        break;
      case self::EDIT:
        if ($this->hasRole($token, 'ROLE_TILTAG_EDIT') && $this->rapportRepository->canEdit($user, $rapport)) {
            return VoterInterface::ACCESS_GRANTED;
        }

        break;
      case self::CREATE:
        if ($this->hasRole($token, 'ROLE_TILTAG_CREATE')) {
            return VoterInterface::ACCESS_GRANTED;
        }

        break;
    }

        return VoterInterface::ACCESS_DENIED;
    }

    private function hasRole(TokenInterface $token, $roleName)
    {
        if (null === $this->roleHierarchy) {
            return \in_array($roleName, $token->getRoles(), true);
        }

        foreach ($this->roleHierarchy->getReachableRoles($token->getRoles()) as $role) {
            if ($roleName === $role->getRole()) {
                return true;
            }
        }

        return false;
    }
}

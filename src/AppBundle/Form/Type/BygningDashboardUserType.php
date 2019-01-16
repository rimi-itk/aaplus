<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BygningType.
 */
class BygningDashboardUserType extends AbstractType
{
    private $doctrine;
    private $userGroup;
    private $userGroupList;

    public function __construct(RegistryInterface $doctrine, $userGroup = null, array $userGroupList = null)
    {
        $this->doctrine = $doctrine;
        $this->userGroup = $userGroup;
        $this->userGroupList = $userGroupList;
    }

    /**
     * @TODO: Missing description.
     *
     * @param FormBuilderInterface $builder
     * @TODO: Missing description.
     *
     * @param array $options
     * @TODO: Missing description.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = $this->userGroup ? $this->getUsersFromGroup($this->userGroup) : $this->getUsersFromGroupList($this->userGroupList);
        $builder->add('username', Filters\ChoiceFilterType::class, ['label' => false, 'choices' => $choices]);
    }

    /**
     * @TODO: Missing description.
     *
     * @param OptionsResolver $resolver
     * @TODO: Missing description.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
        ]);
    }

    public function getParent()
    {
        return Filters\SharedableFilterType::class; // this allow us to use the "add_shared" option
    }

    /**
     * @TODO: Missing description.
     *
     * @return string
     * @TODO: Missing description.
     */
    public function getName()
    {
        return 'dashboard_user';
    }

    private function getUsersFromGroup($groupname)
    {
        $em = $this->doctrine->getRepository('AppBundle:Group');

        $group = $em->findOneByName($groupname);
        $result = [];

        if ($group) {
            $users = $group->getUsers();

            foreach ($users as $user) {
                $result[$user->getUsername()] = $user->getUsername();
            }

            asort($result);
        }

        return $result;
    }

    private function getUsersFromGroupList(array $groups = null)
    {
        $result = [];
        if (\is_array($groups)) {
            foreach ($groups as $group) {
                foreach ($this->getUsersFromGroup($group) as $user) {
                    $result[$user] = $user;
                }
            }
            asort($result);
        }

        return $result;
    }
}

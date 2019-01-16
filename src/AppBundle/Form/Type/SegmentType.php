<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SegmentType extends AbstractType
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('navn')
            ->add('forkortelse')
            ->add('magistrat')
            ->add('segmentAnsvarlig', EntityType::class, [
                'class' => 'AppBundle:User',
                'choices' => $this->getUsersFromGroup('Aa+'),
                'required' => false,
                'placeholder' => 'common.none',
            ])
    ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Segment',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_segment';
    }

    private function getUsersFromGroup($groupname)
    {
        $em = $this->doctrine->getRepository('AppBundle:Group');

        $group = $em->findOneByName($groupname);

        return  $group ? $group->getUsers() : [];
    }
}

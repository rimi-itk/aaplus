<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BygningType.
 */
class BygningSearchType extends AbstractType
{
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
        $builder
      ->add('bygId', 'text', ['label' => false, 'max_length' => 4, 'attr' => ['size' => '4']])
      ->add('navn', null, ['label' => false])
      ->add('adresse', null, ['label' => false])
      ->add('postnummer', null, ['label' => false, 'max_length' => 4, 'attr' => ['size' => '4']])
      ->add('postBy', null, ['label' => false])
      ->add('segment', null, ['label' => false, 'required' => false])
      ->add('status', null, ['label' => false, 'required' => false, 'data' => null])
      ->add('Søg', 'submit');
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
      'data_class' => 'AppBundle\Entity\Bygning',
      'validation_groups' => false,
    ]);
    }

    /**
     * @TODO: Missing description.
     *
     * @return string
     * @TODO: Missing description.
     */
    public function getName()
    {
        return 'bygning';
    }
}

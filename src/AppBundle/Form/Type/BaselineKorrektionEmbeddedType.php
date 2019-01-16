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
 * Class BaselineKorrektionEmbeddedType.
 */
class BaselineKorrektionEmbeddedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('indvirkning', 'checkbox', ['label' => false, 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
      [
          'data_class' => 'AppBundle\Entity\BaselineKorrektion',
      ]
    );
    }

    public function getName()
    {
        return 'appbundle_baselinekorrektion';
    }
}

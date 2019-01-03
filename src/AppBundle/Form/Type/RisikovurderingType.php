<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\DBAL\Types\RisikovurderingType as RisikovurderingEnumType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RisikovurderingType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'choices' => RisikovurderingEnumType::getChoices(),
      'expanded' => true,
      'required' => false,
      'placeholder' => null,
    ]);
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'app_risikovurdering';
    }
}

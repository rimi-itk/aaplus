<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SpecialTiltagDetailType.
 */
class SpecialTiltagDetailType extends TiltagDetailType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('title')
            ->add('kommentar')
            ->add('filepath', 'file', [
                'data_class' => null,
                'attachment_path' => 'filepath',
            ]);

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                // Remove filepath field if not submitted
                if (!isset($data['filepath']) || null === $data['filepath']) {
                    $form->remove('filepath');
                }
            }
    );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\SpecialTiltagDetail',
        ]);
    }

    public function getName()
    {
        return 'appbundle_specialtiltagdetail';
    }
}

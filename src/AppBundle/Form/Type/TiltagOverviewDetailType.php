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
 * Class TiltagOverviewDetailType.
 */
class TiltagOverviewDetailType extends AbstractType
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
        $builder->add('details', 'collection', ['type' => new TiltagDetailEmbeddedType(), 'label' => false]);
        $builder->add('batch_edit_button', 'submit', ['label' => 'tiltag.actions.batch_edit', 'button_class' => 'default', 'disabled' => 'disabled']);
        $builder->add('select_all_button', 'button', ['label' => 'tiltag.actions.batch_select_all']);
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
            'data_class' => 'AppBundle\Entity\Tiltag',
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
        return 'appbundle_tiltag_detail_index';
    }
}

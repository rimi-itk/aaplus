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
 * Class EnergiforsyningType.
 */
class EnergiforsyningType extends AbstractType
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
            ->add('navn', null, [
                'required' => true,
            ])
            ->add('beskrivelse')
            ->add('forsyningsvaerk', null, ['disabled' => true])
            ->add('enhedspris', 'number', ['disabled' => true])
            ->add('prisfaktor')
            ->add('nyEnhedspris', 'number', ['disabled' => true])
            ->add('internProduktioner', 'bootstrap_collection', [
                'property_path' => 'internProduktions',
                'type' => new InternProduktionType(),
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'add_button_text' => 'Add',
                'delete_button_text' => 'Delete',
                'sub_widget_col' => 10,
                'button_col' => 2,
            ]);
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
            'data_class' => 'AppBundle\Entity\Energiforsyning',
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
        return 'appbundle_energiforsyning';
    }
}

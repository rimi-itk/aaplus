<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type\BygningUdtraekType;

use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\EmbeddedFilterTypeInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\SharedableFilterType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RapportType.
 */
class SegmentUdtraekType extends AbstractType implements EmbeddedFilterTypeInterface
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
            ->add('forkortelse', ChoiceFilterType::class, [
                'label' => false,
                // Prevent translation of choice values (cf. https://symfony.com/doc/4.1/reference/forms/types/choice.html#choice-translation-domain)
                'choice_translation_domain' => false,
                'choices' => [
                    'MBU - Børn og Unge' => 'MBU',
                    'MKB - Kultur og borgerservice' => 'MKB',
                    'MSO - Sundhed og omsorg' => 'MSO',
                    'MSB - Social og beskæftigelse' => 'MSB',
                    'MTM - Teknik og Miljø' => 'MTM',
                ],
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
            'data_class' => 'AppBundle\Entity\Segment',
        ]);
    }

    public function getParent()
    {
        return SharedableFilterType::class; // this allow us to use the "add_shared" option
    }

    /**
     * @TODO: Missing description.
     *
     * @return string
     * @TODO: Missing description.
     */
    public function getName()
    {
        return 'filter_segment';
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Tiltag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TiltagType.
 */
class TiltagTilvalgtType extends AbstractType
{
    protected $tiltag;

    // @TODO  public function __construct(Tiltag $tiltag) {
    public function __construct()
    {
//    $this->tiltag = $tiltag;
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
        $builder->add('tilvalgtAfAaPlus', 'choice', [
            'choices' => [
                'Fravalgt' => '0',
                'Tilvalgt' => '1',
            ],
            'placeholder' => '--',
            'required' => false,
        ]);
        $builder->add('tilvalgtbegrundelse', null, ['required' => false]);

        $builder->add('tilvalgtAfMagistrat', 'choice', [
            'choices' => [
                'Fravalgt' => '0',
                'Tilvalgt' => '1',
            ],
            'placeholder' => '--',
            'required' => false,
        ]);
        $builder->add('tilvalgtBegrundelseMagistrat', null, ['required' => false]);
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
        return 'appbundle_tiltagtilvalgt';
    }
}

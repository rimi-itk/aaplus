<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Bilag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TiltagBilagType.
 */
class TiltagBilagType extends AbstractType
{
    protected $bilag;

    // @TODO  public function __construct(Bilag $bilag) {
    public function __construct()
    {
//    $this->bilag = $bilag;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titel')
            ->add('kommentar')
            ->add('tiltag', 'entity', [
                'class' => 'AppBundle:Tiltag',
                'label' => false,
                'attr' => [
                    'class' => 'hidden',
                ],
            ])
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
            'data_class' => 'AppBundle\Entity\Bilag',
        ]);
    }

    public function getName()
    {
        return 'appbundle_tiltag_bilag';
    }
}

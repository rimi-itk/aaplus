<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\BelysningTiltagDetail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BelysningTiltagDetailType.
 */
class BelysningTiltagDetailType extends TiltagDetailType
{
    // @TODO public function __construct(ContainerInterface $container, BelysningTiltagDetail $detail, $isBatchEdit = false) {
    public function __construct(ContainerInterface $container)
    {
        $detail = null;
        $isBatchEdit = false;
        parent::__construct($container, $detail, $isBatchEdit);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
      //->add('tilvalgt')
      ->add('lokale_navn')
      ->add('lokale_type')
      ->add('armaturhoejdeM')
      ->add('rumstoerrelseM2')
      ->add('lokale_antal')
      ->add('drifttidTAar')
      ->add('lyskilde')
      ->add('lyskildeStkArmatur')
      ->add('lyskildeWLyskilde')
      ->add('forkoblingStkArmatur')
      ->add('armaturerStkLokale')
      ->add('placering')
      ->add('styring')
      ->add('nyStyring', 'entity', [
        'class' => 'AppBundle:BelysningTiltagDetail\NyStyring',
        'choices' => $this->getAktuelNyStyring(),
        'required' => false,
        'placeholder' => 'common.none',
      ])
      ->add('erstatningsLyskilde')
      ->add('nytArmatur')
      ->add('noter')
      ->add('noterForNyBelysning')
      ->add('belysningstiltag')
      ->add('nyeSensorerStkLokale')
      ->add('standardinvestSensorKrStk')
      ->add('reduktionAfDrifttid', 'percent', ['scale' => 2, 'required' => false])
      ->add('standardinvestArmaturKrStk')
      ->add('standardinvestLyskildeKrStk')
      ->add('nyLyskilde')
      ->add('nyLyskildeStkArmatur')
      ->add('nyLyskildeWLyskilde')
      ->add('nyForkoblingStkArmatur')
      ->add('nyeArmaturerStkLokale')
      ->add('nyttiggjortVarmeAfElBesparelse', 'percent', ['scale' => 2, 'required' => false])
      ->add('prisfaktor')
      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => 'AppBundle\Entity\BelysningTiltagDetail',
    ]);
    }

    public function getName()
    {
        return 'appbundle_belysningtiltagdetail';
    }

    private function getAktuelNyStyring()
    {
        $em = $this->doctrine->getRepository('AppBundle:BelysningTiltagDetail\NyStyring');

        return $em->findActive();
    }
}

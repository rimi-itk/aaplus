<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\DBAL\Types\KlimaskaermType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class KlimaskaermTiltagDetailType.
 */
class KlimaskaermTiltagDetailType extends TiltagDetailType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
      ->add('laastAfEnergiraadgiver', null, [
        'required' => false,
      ])
      ->add('klimaskaerm', 'entity', [
        'class' => 'AppBundle:Klimaskaerm',
        'choices' => $this->getKlimaskaerme(),
        'required' => false,
        'empty_data' => null,
      ])
      ->add('klimaskaermOverskrevetPris')
      ->add('type')
      ->add('placering')
      ->add('hoejdeElLaengdeM')
      ->add('breddeM')
      ->add('antalStk')
      ->add('andelAfArealDerEfterisoleres', 'percent', ['scale' => 2, 'required' => false])
      ->add('uEksWM2K')
      ->add('uNyWM2K')
      ->add('tIndeC')
      ->add('tUdeC')
      ->add('tOpvarmningTimerAar')
      ->add('yderligereBesparelserPct', 'percent', ['scale' => 2, 'required' => false])
      ->add('prisfaktor')
      ->add('noterTilPrisfaktorValgteLoesningTiltagSpecielleForholdPaaStedet', 'textarea', [
        'attr' => ['maxlength' => 360], 'required' => false,
      ])
      ->add('levetidAar')
      ->add('noteGenerelt', 'textarea', ['attr' => ['maxlength' => 360], 'required' => false])
      ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
      'data_class' => 'AppBundle\Entity\KlimaskaermTiltagDetail',
    ]);
    }

    public function getName()
    {
        return 'appbundle_klimaskaermtiltagdetail';
    }

    private function getKlimaskaerme()
    {
        $repository = $this->container->get('doctrine')->getRepository('AppBundle:Klimaskaerm');

        $result = $repository->findByType($this instanceof VindueTiltagDetailType ? KlimaskaermType::VINDUE : KlimaskaermType::KLIMASKAERM);

        return $result;
    }
}

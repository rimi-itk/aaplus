<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Pumpe;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PumpeTiltagDetailType.
 */
class PumpeTiltagDetailType extends TiltagDetailType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('pumpe')
            ->add('pumpeID')
            ->add('forsyningsomraade')
            ->add('placering')
            ->add('applikation')
            ->add('isoleringskappe', null, ['required' => false])
            ->add('nyttiggjortVarme', null, [
                'required' => true,
            ])
            ->add('noter', null, ['required' => false])
            ->add('eksisterendeDrifttid')
            ->add('nyDrifttid')
            ->add('prisfaktor')
            ->add('overskrevetPris', null, ['required' => false])
            ->add('varmetabIftAekvivalentRoerstoerrelse', 'choice', [
                'choices' => $this->getRoerstoerrelser(),
                'required' => false,
            ]);

        // @FIXME: Workaround for the field "B-Faktor" being deprecated.
        $empty_value = $this->isBatchEdit ? '--' : '*** Gammel B-Faktor: '.number_format($this->detail->getBFaktor(), 2, ',', '.').' ***';
        $attr = $this->isBatchEdit ? [] : [
            'help_text' => 'Bemærk: Feltet "B-Faktor" er blevet erstattet af "Nyttiggjort varme". Vælg venligst "Nyttiggjort varme" ovenfor.',
            'class' => 'aaplus-deprecated',
        ];

        if (!$this->detail->getNyttiggjortVarme()) {
            $builder
                ->remove('nyttiggjortVarme')
                ->add('nyttiggjortVarme', null, [
                    'required' => true,
                    'placeholder' => $empty_value,
                    'attr' => $attr,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\PumpeTiltagDetail',
        ]);
    }

    public function getName()
    {
        return 'appbundle_pumpetiltagdetail';
    }

    private function getRoerstoerrelser()
    {
        $options = [];
        foreach (Pumpe::$varmetabstabel as $value => $item) {
            $options[$value] = $value.' / '.$item[0].' mm';
        }

        return $options;
    }
}

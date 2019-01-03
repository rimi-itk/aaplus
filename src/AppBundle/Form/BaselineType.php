<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BaselineType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('arealdataPrimaerKilde')
            ->add('arealdataPrimaerAreal')
            ->add('arealdataPrimaerNoter', 'textarea', ['attr' => ['maxlength' => 750], 'required' => false])
            ->add('arealdataSekundaerKilde')
            ->add('arealdataSekundaerAreal')
            ->add('arealdataSekundaerNoter', 'textarea', ['attr' => ['maxlength' => 750], 'required' => false])
            ->add('arealTilNoegletalsanalyse')
            ->add('elForbrugsdataPrimaerKilde')
            ->add('elForbrugsdataPrimaer1Aarstal')
            ->add('elForbrugsdataPrimaer1Forbrug')
            ->add('elForbrugsdataPrimaer2Aarstal')
            ->add('elForbrugsdataPrimaer2Forbrug')
            ->add('elForbrugsdataPrimaer3Aarstal')
            ->add('elForbrugsdataPrimaer3Forbrug')
            ->add('elForbrugsdataPrimaerNoter', 'textarea', ['attr' => ['maxlength' => 750], 'required' => false])
            ->add('elForbrugsdataSekundaerKilde')
            ->add('elForbrugsdataSekundaer1Aarstal')
            ->add('elForbrugsdataSekundaer1Forbrug')
            ->add('elForbrugsdataSekundaer2Aarstal')
            ->add('elForbrugsdataSekundaer2Forbrug')
            ->add('elForbrugsdataSekundaer3Aarstal')
            ->add('elForbrugsdataSekundaer3Forbrug')
            ->add('elForbrugsdataSekundaerNoter', 'textarea', ['attr' => ['maxlength' => 750], 'required' => false])
            ->add('elBaselineFastsatForEjendom')
            ->add('elBaselineNoter', 'textarea', ['attr' => ['maxlength' => 750], 'required' => false])
            ->add('varmeForbrugsdataPrimaerKilde')
            ->add('varmeForbrugsdataPrimaer1Aarstal')
            ->add('varmeForbrugsdataPrimaer1Forbrug')
            ->add('varmeForbrugsdataPrimaer2Aarstal')
            ->add('varmeForbrugsdataPrimaer2Forbrug')
            ->add('varmeForbrugsdataPrimaer3Aarstal')
            ->add('varmeForbrugsdataPrimaer3Forbrug')
            ->add('varmeForbrudsdataPrimaerGUFForbrugFastsaettesEfter')
            ->add('varmeForbrugsdataPrimaer1SamletVarmeforbrugJuniJuliAugust')
            ->add('varmeForbrugsdataPrimaer2SamletVarmeforbrugJuniJuliAugust')
            ->add('varmeForbrugsdataPrimaer3SamletVarmeforbrugJuniJuliAugust')
            ->add('varmeForbrugsdataPrimaer1GDPeriode')
            ->add('varmeForbrugsdataPrimaer2GDPeriode')
            ->add('varmeForbrugsdataPrimaer3GDPeriode')
            ->add('varmeForbrugsdataPrimaerNoter', 'textarea', ['attr' => ['maxlength' => 750], 'required' => false])
            ->add('varmeForbrugsdataSekundaerKilde')
            ->add('varmeForbrugsdataSekundaer1Aarstal')
            ->add('varmeForbrugsdataSekundaer1Forbrug')
            ->add('varmeForbrugsdataSekundaer2Aarstal')
            ->add('varmeForbrugsdataSekundaer2Forbrug')
            ->add('varmeForbrugsdataSekundaer3Aarstal')
            ->add('varmeForbrugsdataSekundaer3Forbrug')
            ->add('varmeForbrudsdataSekundaerGUFForbrugFastsaettesEfter')
            ->add('varmeForbrugsdataSekundaer1SamletVarmeforbrugJuniJuliAugust')
            ->add('varmeForbrugsdataSekundaer2SamletVarmeforbrugJuniJuliAugust')
            ->add('varmeForbrugsdataSekundaer3SamletVarmeforbrugJuniJuliAugust')
            ->add('varmeForbrugsdataSekundaer1GDPeriode')
            ->add('varmeForbrugsdataSekundaer2GDPeriode')
            ->add('varmeForbrugsdataSekundaer3GDPeriode')
            ->add('varmeForbrugsdataSekundaerNoter', 'textarea', ['attr' => ['maxlength' => 750], 'required' => false])
            ->add('varmeGAFForbrug')
            ->add('varmeGUFForbrug')
            ->add('varmeStrafafkoelingsafgift')
            ->add('varmeBaselineNoter', 'textarea', ['attr' => ['maxlength' => 750], 'required' => false])
            ->add('bygning', null, ['disabled' => 'disabled', 'required' => false])
            ->add('eloKategori')
            ->add('save_changed', 'submit', [
                'disabled' => 'disabled',
                'label' => 'appbundle.baseline.changed_submit',
                'attr' => ['icon' => 'calculator'],
            ]);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Baseline',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_baseline';
    }
}

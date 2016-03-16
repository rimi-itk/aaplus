<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BaselineType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('arealdataPrimaerKilde')
            ->add('arealdataPrimaerAreal')
            ->add('arealdataPrimaerNoter')
            ->add('arealdataSekundaerKilde')
            ->add('arealdataSekundaerAreal')
            ->add('arealdataSekundaerNoter')
            ->add('arealTilNoegletalsanalyse')
            ->add('elForbrugsdataPrimaerKilde')
            ->add('elForbrugsdataPrimaer1Aarstal')
            ->add('elForbrugsdataPrimaer1Forbrug')
            ->add('elForbrugsdataPrimaer2Aarstal')
            ->add('elForbrugsdataPrimaer2Forbrug')
            ->add('elForbrugsdataPrimaer3Aarstal')
            ->add('elForbrugsdataPrimaer3Forbrug')
            ->add('elForbrugsdataPrimaerGennemsnit', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('elForbrugsdataPrimaerNoegetal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('elForbrugsdataPrimaerNoter')
            ->add('elForbrugsdataSekundaerKilde')
            ->add('elForbrugsdataSekundaer1Aarstal')
            ->add('elForbrugsdataSekundaer1Forbrug')
            ->add('elForbrugsdataSekundaer2Aarstal')
            ->add('elForbrugsdataSekundaer2Forbrug')
            ->add('elForbrugsdataSekundaer3Aarstal')
            ->add('elForbrugsdataSekundaer3Forbrug')
            ->add('elForbrugsdataSekundaerGennemsnit', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('elForbrugsdataSekundaerNoegetal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('elForbrugsdataSekundaerNoter')
            ->add('elBaselineFastsatForEjendom')
            ->add('elBaselineNoegletalForEjendom')
            ->add('elBaselineNoter')
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
            ->add('varmeForbrugsdataPrimaer1GUFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer2GUFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer3GUFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer1GAFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer2GAFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer3GAFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer1GDPeriode')
            ->add('varmeForbrugsdataPrimaer2GDPeriode')
            ->add('varmeForbrugsdataPrimaer3GDPeriode')
            ->add('varmeForbrugsdataPrimaer1GAFnormal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer2GAFnormal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer3GAFnormal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer1ForbrugKlimakorrigeret', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer2ForbrugKlimakorrigeret', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaer3ForbrugKlimakorrigeret', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaerGAFGennemsnit', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaerGUFGennemsnit', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaerGennemsnitKlimakorrigeret', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaerNoegletal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataPrimaerNoter')
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
            ->add('varmeForbrugsdataSekundaer1GUFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer2GUFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer3GUFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer1GAFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer2GAFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer3GAFRegAar', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer1GDPeriode')
            ->add('varmeForbrugsdataSekundaer2GDPeriode')
            ->add('varmeForbrugsdataSekundaer3GDPeriode')
            ->add('varmeForbrugsdataSekundaer1GAFnormal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer2GAFnormal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer3GAFnormal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer1ForbrugKlimakorrigeret', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer2ForbrugKlimakorrigeret', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaer3ForbrugKlimakorrigeret', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaerGAFGennemsnit', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaerGUFGennemsnit', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaerGennemsnitKlimakorrigeret', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaerNoegletal', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeForbrugsdataSekundaerNoter')
            ->add('varmeGAFForbrug')
            ->add('varmeGUFForbrug')
            ->add('varmeBaselineFastsatForEjendom', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeBaselineNoegletalForEjendom', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('varmeStrafafkoelingsafgift')
            ->add('varmeBaselineNoter')
            ->add('bygning', 'text', array('disabled' => 'disabled', 'required' => false))
            ->add('eloKategori')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Baseline'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_baseline';
    }
}

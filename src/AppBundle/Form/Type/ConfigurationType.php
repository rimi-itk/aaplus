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
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class ConfigurationType.
 */
class ConfigurationType extends AbstractType
{
    protected $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
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
        if ($this->authorizationChecker && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder
        ->add('rapportKalkulationsrente', 'percent', ['scale' => 2]);
        }

        $builder
      ->add('rapportDriftomkostningerfaktor')
      ->add('rapportInflation')
      ->add('rapportLobetid')
      ->add('rapportProcentAfInvestering', 'percent', ['scale' => 2])
      ->add('rapportNominelEnergiprisstigning')

      ->add('tekniskisoleringVarmeledningsevneEksistLamelmaatter')
      ->add('tekniskisoleringVarmeledningsevneNyIsolering')

      ->add('solcelletiltagdetailEnergiprisstigningPctPrAar', 'percent', ['scale' => 2])
      ->add('solcelletiltagdetailSalgsprisFoerste10AarKrKWh')
      ->add('solcelletiltagdetailSalgsprisEfter10AarKrKWh')

      ->add('mtmFaellesomkostningerGrundpris')
      ->add('mtmFaellesomkostningerPrisPrM2')
      ->add('mtmFaellesomkostningerNulHvisArealMindreEnd')
      ->add('mtmFaellesomkostningerNulHvisTotalEntreprisesumMindreEnd');
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
      'data_class' => 'AppBundle\Entity\Configuration',
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
        return 'appbundle_configuration';
    }
}

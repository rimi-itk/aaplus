<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\AppBundle;
use AppBundle\DBAL\Types\BygningStatusType;
use AppBundle\Entity\KlimaskaermTiltag;
use AppBundle\Entity\PumpeTiltag;
use AppBundle\Entity\SolcelleTiltag;
use AppBundle\Entity\SpecialTiltag;
use AppBundle\Entity\TekniskIsoleringTiltag;
use AppBundle\Entity\Tiltag;
use AppBundle\Entity\VindueTiltag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class TiltagType.
 */
class TiltagType extends AbstractType
{
    protected $tiltag;
    protected $authorizationChecker;

    // @TODO    public function __construct(Tiltag $tiltag, AuthorizationCheckerInterface $authorizationChecker) {
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
//        $this->tiltag = $tiltag;
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
        if ($this->authorizationChecker && !$this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->add('tilvalgtAfRaadgiver');
        } else {
            $builder->add('tilvalgtAfAaPlus', 'choice', [
                'choices' => [
                    'Fravalgt' => '0',
                    'Tilvalgt' => '1',
                ],
                'placeholder' => '--',
                'required' => false,
            ]);
            $builder->add('tilvalgtAfMagistrat', 'choice', [
                'choices' => [
                    'Fravalgt' => '0',
                    'Tilvalgt' => '1',
                ],
                'placeholder' => '--',
                'required' => false,
            ]);
            $builder->add('tilvalgtbegrundelse', null, ['required' => false]);
            $builder->add('tilvalgtBegrundelseMagistrat', null, ['required' => false]);

            $status = $this->tiltag->getRapport()->getBygning()->getStatus();

            // Dato for drift
            if (BygningStatusType::UNDER_UDFOERSEL === $status || BygningStatusType::DRIFT === $status) {
                $builder->add('datoForDrift', 'date', [
                    // render as a single text box
                    'widget' => 'single_text',
                    'required' => false,
                ]);
            }

            // Energiledelse faktor/noter
            if (BygningStatusType::DRIFT === $status) {
                $builder->add('energiledelseAendringIBesparelseFaktor', 'percent', ['required' => false]);
                $builder->add('energiledelseNoter');
            }
        }
        $builder->add('title')
            ->add('faktorForReinvesteringer')
            ->add('opstartsomkostninger');

        $builder
            ->add('genopretning')
            ->add('genopretningForImplementeringsomkostninger')
            ->add('modernisering')
            ->add('reelAnlaegsinvestering');

        $builder->add('reelAnlaegsinvestering')
            ->add('forsyningVarme', 'entity', [
                'class' => 'AppBundle:Energiforsyning',
                'choices' => $this->tiltag->getRapport()->getEnergiforsyninger(),
                'required' => false,
            ])
            ->add('forsyningEl', 'entity', [
                'class' => 'AppBundle:Energiforsyning',
                'choices' => $this->tiltag->getRapport()->getEnergiforsyninger(),
                'required' => false,
            ])
            ->add('beskrivelseNuvaerende', 'textarea', ['attr' => ['maxlength' => 850], 'required' => false])
            ->add('beskrivelseForslag', 'textarea', ['attr' => ['maxlength' => 1000], 'required' => false])
            ->add('beskrivelseOevrige', 'textarea', ['attr' => ['maxlength' => 1100], 'required' => false])
            ->add('risikovurdering', 'textarea', ['attr' => ['maxlength' => 360], 'required' => false])
            ->add('placering', 'textarea', ['attr' => ['maxlength' => 120], 'required' => false])
            ->add('beskrivelseDriftOgVedligeholdelse', 'textarea', ['attr' => ['maxlength' => 360], 'required' => false])
            ->add('indeklima', 'textarea', ['attr' => ['maxlength' => 360], 'required' => false]);

        $builder->add('risikovurderingTeknisk', new RisikovurderingType(), []);
        $builder->add('risikovurderingBrugsmoenster', new RisikovurderingType(), []);
        $builder->add('risikovurderingDatagrundlag', new RisikovurderingType(), []);
        $builder->add('risikovurderingDiverse', new RisikovurderingType(), []);
        $builder->add('risikovurderingAendringIBesparelseFaktor', 'percent', ['required' => false]);
        $builder->add('risikovurderingOekonomiskKompenseringIftInvesteringFaktor', 'percent', ['required' => false]);

        if ($this->tiltag instanceof TekniskIsoleringTiltag) {
            $builder
                ->add('besparelseDriftOgVedligeholdelse')
                ->add('besparelseStrafafkoelingsafgift')
                ->add('levetid');
        }
        if ($this->tiltag instanceof PumpeTiltag) {
            $builder
                ->add('besparelseDriftOgVedligeholdelse')
                ->add('levetid');
        }
        if ($this->tiltag instanceof VindueTiltag) {
            $builder
                ->add('besparelseDriftOgVedligeholdelse');
        } elseif ($this->tiltag instanceof SolcelleTiltag) {
            $builder
                ->add('levetid');
        } elseif ($this->tiltag instanceof KlimaskaermTiltag) {
            $builder
                ->add('besparelseDriftOgVedligeholdelse');
        } elseif ($this->tiltag instanceof SpecialTiltag) {
            $builder
                ->add('besparelseDriftOgVedligeholdelse')
                ->add('besparelseStrafafkoelingsafgift')
                ->add('anlaegsinvesteringExRisiko')
                ->add('besparelseGUF')
                ->add('besparelseGAF')
                ->add('besparelseEl')
                ->add('yderligereBesparelse')
                ->add('levetid');

            $builder->add('primaerEnterprise')
                ->add('tiltagskategori');
        }
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
        return 'appbundle_tiltag';
    }
}

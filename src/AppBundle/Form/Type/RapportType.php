<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Rapport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class RapportType.
 */
class RapportType extends AbstractType
{
    protected $authorizationChecker;
    protected $rapport;

    // @TODO  public function __construct(AuthorizationCheckerInterface $authorizationChecker, Rapport $rapport)
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
//    $this->rapport = $rapport;
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
        // If there is a Baseline attached disable editing of baseline fields
        $disabled = $this->rapport->getBygning()->getBaseline() ? 'disabled' : '';

        $builder
            ->add(
          'datering',
          'date',
          [
              // render as a single HTML5 text box
              'widget' => 'single_text', ]
      )
            ->add('BaselineEl', null, ['disabled' => $disabled])
            ->add('BaselineVarmeGUF', null, ['disabled' => $disabled])
            ->add('BaselineVarmeGAF', null, ['disabled' => $disabled])
            ->add('BaselineStrafAfkoeling', null, ['disabled' => $disabled])
            ->add('bygning', new BygningBaselineEmbedType(), ['label' => false])
            ->add('faktorPaaVarmebesparelse')
            ->add('energiscreening');

        if ($this->authorizationChecker && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->add('elena');
        }

        if ($this->authorizationChecker && $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('ava');
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
            'data_class' => 'AppBundle\Entity\Rapport',
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
        return 'appbundle_rapport';
    }
}

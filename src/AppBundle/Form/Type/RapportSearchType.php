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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class RapportType.
 */
class RapportSearchType extends AbstractType
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
        $builder
            ->add('bygning', RapportSearchBygningEmbedType::class, ['label' => false])
            ->add('version', TextType::class, ['label' => false])
            ->add('datering', TextType::class, ['label' => false]);

        if ($this->authorizationChecker && $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $builder->add('elena', ChoiceType::class, [
                'choices' => [
                    'Nej' => '0',
                    'Ja' => '1',
                ],
                'placeholder' => '--',
                'required' => false,
                'label' => false,
            ]);
            $builder->add('ava', ChoiceType::class, [
                'choices' => [
                    'Nej' => '0',
                    'Ja' => '1',
                ],
                'placeholder' => '--',
                'required' => false,
                'label' => false,
            ]);
        }

        $builder->add('Søg', SubmitType::class);
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

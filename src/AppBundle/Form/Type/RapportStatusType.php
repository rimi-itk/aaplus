<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\DBAL\Types\BygningStatusType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class RapportType.
 */
class RapportStatusType extends AbstractType
{
    protected $authorizationChecker;
    protected $status;

    // @TODO    public function __construct(AuthorizationCheckerInterface $authorizationChecker, $status)
    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
//    $this->status = $status;
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
        if (BygningStatusType::UNDER_UDFOERSEL === $this->status && $this->authorizationChecker && $this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('ava', 'choice', [
                'choices' => [
                    'Ikke ansøgt AVA-støtte' => '0',
                    'Ansøgt om AVA-støtte ' => '1',
                ],
                'placeholder' => '--',
                'required' => true,
            ]);
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
        return 'appbundle_rapport_status';
    }
}

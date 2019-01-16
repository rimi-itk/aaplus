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
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class BygningType.
 */
class BygningTilknytRaadgiverType extends AbstractType
{
    protected $authorizationChecker;

    private $doctrine;

    public function __construct(RegistryInterface $doctrine, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->doctrine = $doctrine;
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
            ->add('aaplusAnsvarlig', 'entity', [
                'class' => 'AppBundle:User',
                'choices' => $this->getUsersFromGroup('Aa+'),
                'required' => false,
                'placeholder' => 'common.none',
            ])
            ->add('energiRaadgiver', 'entity', [
                'class' => 'AppBundle:User',
                'choices' => $this->getUsersFromGroup('Rådgiver'),
                'required' => false,
                'placeholder' => 'common.none',
            ])
            ->add('status', 'hidden', [
                'read_only' => true,
            ])
            ->add(
          'rapport',
          new RapportEmbedType($this->authorizationChecker),
          [
              'by_reference' => true,
              'data_class' => 'AppBundle\Entity\Rapport',
          ]
      );
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
            'data_class' => 'AppBundle\Entity\Bygning',
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();

                if (BygningStatusType::DATA_VERIFICERET === $data->getStatus()) {
                    return ['Default', 'DATA_VERIFICERET'];
                }

                if (BygningStatusType::TILKNYTTET_RAADGIVER === $data->getStatus()) {
                    return ['Default', 'TILKNYTTET_RAADGIVER'];
                }

                return ['Default'];
            },
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
        return 'appbundle_bygning';
    }

    private function getUsersFromGroup($groupname)
    {
        $em = $this->doctrine->getRepository('AppBundle:Group');

        $group = $em->findOneByName($groupname);

        return $group->getUsers();
    }
}

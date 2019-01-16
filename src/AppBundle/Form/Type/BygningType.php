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
class BygningType extends AbstractType
{
    private $doctrine;
    private $authorizationChecker;

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
      ->add('bygId')
      ->add('navn')
      ->add('OpfoerselsAar')
      ->add('enhedsys')
      ->add('type')
      ->add('adresse')
      ->add('postnummer')
      ->add('postBy')
      ->add('afdelingsnavn')
      ->add('ejerA')
      ->add('anvendelse')
      ->add('bruttoetageareal')
      ->add('forsyningsvaerkVarme', 'entity', [
        'class' => 'AppBundle:Forsyningsvaerk',
        'required' => false,
        'placeholder' => '--',
      ])
      ->add('forsyningsvaerkEl', 'entity', [
        'class' => 'AppBundle:Forsyningsvaerk',
        'required' => false,
        'placeholder' => '--',
      ])
      ->add('divisionnavn')
      ->add('omraadenavn')
      ->add('ejerforhold')
      ->add('segment', 'entity', [
        'class' => 'AppBundle:Segment',
        'required' => false,
        'placeholder' => '--',
      ])
      ->add('aaplusAnsvarlig', 'entity', [
        'class' => 'AppBundle:User',
        'choices' => $this->getUsersFromGroup('Aa+'),
        'required' => false,
        'placeholder' => 'common.none',
      ])
      ->add('projektleder', 'entity', [
        'class' => 'AppBundle:User',
        'choices' => $this->getUsersFromGroup('Projektleder'),
        'required' => false,
        'placeholder' => 'common.none',
      ])
      ->add('energiRaadgiver', 'entity', [
        'class' => 'AppBundle:User',
        'choices' => $this->getUsersFromGroup('Rådgiver'),
        'required' => false,
        'placeholder' => 'common.none',
      ])
      ->add('projekterende', 'entity', [
        'class' => 'AppBundle:User',
        'choices' => $this->getUsersFromGroup('Projekterende'),
        'required' => false,
        'placeholder' => 'common.none',
      ])
      ->add('users', null, [
        'expanded' => true,
        'choices' => $this->getUsersFromGroup('Interessent'),
        ]);

        // Only show the editable status field to super admins
        if ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('status');
        } else {
            $builder->add('status', 'hidden', [
        'read_only' => true,
      ]);
        }

        //->add('users', null, array('by_reference' => false, 'expanded' => true , 'multiple' => true));
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
          } elseif (BygningStatusType::TILKNYTTET_RAADGIVER === $data->getStatus()) {
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

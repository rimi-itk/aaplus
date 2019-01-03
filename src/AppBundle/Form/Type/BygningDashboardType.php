<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\Form\Type\BygningUdtraekType\SegmentUdtraekType;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BygningType.
 */
class BygningDashboardType extends AbstractType
{
    private $type;

    private $doctrine;

    // @TODO  public function __construct(RegistryInterface $doctrine, $filterCondition='aaplusAnsvarlig') {
    public function __construct(RegistryInterface $doctrine)
    {
//    $this->type = $filterCondition;
        $this->doctrine = $doctrine;
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
      ->add('navn', Filters\TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_BOTH, 'label' => false])
      ->add('adresse', Filters\TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_BOTH, 'label' => false])
      ->add('postnummer', Filters\TextFilterType::class, ['condition_pattern' => FilterOperands::STRING_STARTS, 'label' => false])
      ->add('status', null, ['required' => false, 'label' => false]);

        // @TODO
        $builder->add('segment', SegmentUdtraekType::class, ['label' => false,
      'add_shared' => function (FilterBuilderExecuterInterface $qbe) {
          $closure = function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
              $filterBuilder->leftJoin($alias.'.segment', $joinAlias);
          };

          $qbe->addOnce($qbe->getAlias().'.segment', 'seg', $closure);
      },
    ]);

        if ('aaplusAnsvarlig' === $this->type) {
            // @TODO      $builder->add('energiRaadgiver', new BygningDashboardUserType($this->doctrine, "Rådgiver"), array('label' => false,
            $builder->add('energiRaadgiver', BygningDashboardUserType::class, ['label' => false,
        'add_shared' => function (FilterBuilderExecuterInterface $qbe) {
            $closure = function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
                $filterBuilder->leftJoin($alias.'.energiRaadgiver', $joinAlias);
            };

            $qbe->addOnce($qbe->getAlias().'.energiRaadgiver', 'u', $closure);
        },
      ]);
        } else {
            // @TODO      $builder->add('aaplusAnsvarlig', new BygningDashboardUserType($this->doctrine, "Aa+"), array('label' => false,
            $builder->add('aaplusAnsvarlig', BygningDashboardUserType::class, ['label' => false,
        'add_shared' => function (FilterBuilderExecuterInterface $qbe) {
            $closure = function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
                $filterBuilder->leftJoin($alias.'.aaplusAnsvarlig', $joinAlias);
            };

            $qbe->addOnce($qbe->getAlias().'.aaplusAnsvarlig', 'u', $closure);
        },
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
      'data_class' => 'AppBundle\Entity\Bygning',
      'csrf_protection' => false,
      'validation_groups' => ['filtering'], // avoid NotBlank() constraint-related message
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
        return 'dashboard_bygning';
    }
}

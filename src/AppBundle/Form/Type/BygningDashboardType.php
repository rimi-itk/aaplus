<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Form\Type;

use Lexik\Bundle\FormFilterBundle\Filter\FilterOperands;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\DBAL\Types\BygningStatusType;
use AppBundle\Form\Type\BygningUdtraekType\SegmentUdtraekType;

use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;

use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderExecuterInterface;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class BygningType
 * @package AppBundle\Form
 */
class BygningDashboardType extends AbstractType {
private $type;

  private $doctrine;

// @TODO  public function __construct(RegistryInterface $doctrine, $filterCondition='aaplusAnsvarlig') {
  public function __construct(RegistryInterface $doctrine) {
//    $this->type = $filterCondition;
    $this->doctrine = $doctrine;
  }

  /**
   * @TODO: Missing description.
   *
   * @param FormBuilderInterface $builder
   *   @TODO: Missing description.
   * @param array $options
   *   @TODO: Missing description.
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder
      ->add('navn', Filters\TextFilterType::class, array('condition_pattern' => FilterOperands::STRING_BOTH, 'label' => false))
      ->add('adresse', Filters\TextFilterType::class, array('condition_pattern' => FilterOperands::STRING_BOTH, 'label' => false))
      ->add('postnummer', Filters\TextFilterType::class, array('condition_pattern' => FilterOperands::STRING_STARTS, 'label' => false))
      ->add('status', null, array('required' => false, 'label' => false));

    // @TODO
    $builder->add('segment', SegmentUdtraekType::class, array('label' => false,
      'add_shared' => function (FilterBuilderExecuterInterface $qbe) {
        $closure = function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
          $filterBuilder->leftJoin($alias . '.segment', $joinAlias);
        };

        $qbe->addOnce($qbe->getAlias().'.segment', 'seg', $closure);
      }
    ));

    if($this->type === 'aaplusAnsvarlig') {
// @TODO      $builder->add('energiRaadgiver', new BygningDashboardUserType($this->doctrine, "Rådgiver"), array('label' => false,
      $builder->add('energiRaadgiver', BygningDashboardUserType::class, array('label' => false,
        'add_shared' => function (FilterBuilderExecuterInterface $qbe) {
          $closure = function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
            $filterBuilder->leftJoin($alias . '.energiRaadgiver', $joinAlias);
          };

          $qbe->addOnce($qbe->getAlias() . '.energiRaadgiver', 'u', $closure);
        }
      ));
    } else {
// @TODO      $builder->add('aaplusAnsvarlig', new BygningDashboardUserType($this->doctrine, "Aa+"), array('label' => false,
      $builder->add('aaplusAnsvarlig', BygningDashboardUserType::class, array('label' => false,
        'add_shared' => function (FilterBuilderExecuterInterface $qbe) {
          $closure = function (QueryBuilder $filterBuilder, $alias, $joinAlias, Expr $expr) {
            $filterBuilder->leftJoin($alias . '.aaplusAnsvarlig', $joinAlias);
          };

          $qbe->addOnce($qbe->getAlias() . '.aaplusAnsvarlig', 'u', $closure);
        }
      ));
    }

    $builder->add('Søg', SubmitType::class);
  }

  /**
   * @TODO: Missing description.
   *
   * @param OptionsResolver $resolver
   *   @TODO: Missing description.
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\Bygning',
      'csrf_protection'   => false,
      'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
    ));
  }

  /**
   * @TODO: Missing description.
   *
   * @return string
   *   @TODO: Missing description.
   */
  public function getName() {
    return 'dashboard_bygning';
  }
}

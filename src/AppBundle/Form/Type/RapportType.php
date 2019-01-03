<?php
/**
 * @file
 * @TODO: Missing description.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\Rapport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class RapportType
 * @package AppBundle\Form
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
   * @param array $options
   * @TODO: Missing description.
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {

    // If there is a Baseline attached disable editing of baseline fields
    $disabled = $this->rapport->getBygning()->getBaseline() ? 'disabled' : '';

    $builder
      ->add('datering', 'date', array(
        // render as a single HTML5 text box
        'widget' => 'single_text')
      )
      ->add('BaselineEl', null, array('disabled' => $disabled))
      ->add('BaselineVarmeGUF', null, array('disabled' => $disabled))
      ->add('BaselineVarmeGAF', null, array('disabled' => $disabled))
      ->add('BaselineStrafAfkoeling', null, array('disabled' => $disabled))
      ->add('bygning', new BygningBaselineEmbedType(), array('label' => false))
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
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\Rapport'
    ));
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

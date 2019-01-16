<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Form\Type;

use AppBundle\Entity\TiltagDetail;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TiltagDetailType.
 */
class TiltagDetailType extends AbstractType
{
    protected $container;
    protected $authorizationChecker;
    protected $detail;
    protected $isBatchEdit;
    protected $doctrine;

    // @TODO public function __construct(ContainerInterface $container, TiltagDetail $detail, $isBatchEdit = false)
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->doctrine = $this->container->get('doctrine');
        $this->authorizationChecker = $this->container->get('security.context');
//    $this->detail = $detail;
//    $this->isBatchEdit = $isBatchEdit;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('tilvalgt');
        $builder->add('ikkeElenaBerettiget');

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'modifyForBatchEdit']);
    }

    public function modifyForBatchEdit(FormEvent $event)
    {
        if ($this->isBatchEdit) {
            $form = $event->getForm();
            foreach ($event->getForm()->all() as $child) {
                $config = $child->getConfig();
                $options = $config->getOptions();
                $type = $config->getType()->getName();
                $name = $config->getName();

                // Alter checkbox to dropdown
                if ('checkbox' === $type) {
                    $options['choice_value'] = null;
                    unset($options['value']);
                    $form->add(
          // Replace original field...
            $name,
            'choice',
            // while keeping the original options...
            array_replace(
              $options,
              [
                  // replacing specific ones
                  'required' => false,
                  'choices' => [
                      'Nej' => '0',
                      'Ja' => '1',
                  ],
                  'empty_data' => null,
                  'placeholder' => '--',
              ]
            )
                    );
                } else {
                    // Set all as "Not required"
                    $form->add(
                        // Replace original field...
            $name,
            $type,
            // while keeping the original options...
            array_replace(
              $options,
              [
                  // replacing specific ones
                  'required' => false,
              ]
            )
          );
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
      [
          'data_class' => 'AppBundle\Entity\TiltagDetail',
      ]
    );
    }

    public function getName()
    {
        return 'appbundle_tiltagdetail';
    }

    /**
     * Insert form field after a specific field.
     *
     * @param formBuilderInterface        $builder
     *                                               The form builder
     * @param FormBuilderInterface|string $reference
     *                                               The field to insert after
     * @param array                       $newFields
     *                                               The list of fields to insert.
     *                                               Each field must be an array with
     *                                               arguments suitable for calling FormBuilderInterface::create or a field
     *                                               created by FormBuilderInterface::create
     *
     * @return formBuilderInterface
     *                              The form builder
     */
    protected function insertAfter(FormBuilderInterface $builder, $reference, array $newFields)
    {
        $allFields = $builder->all();
        foreach ($allFields as $name => $field) {
            $builder->remove($name);
        }

        $inserted = false;
        foreach ($allFields as $name => $field) {
            $builder->add($field);
            if ($name === $reference || $field === $reference) {
                $this->addFields($builder, $newFields);
                $inserted = true;
            }
        }

        if (!$inserted) {
            $this->addFields($builder, $newFields);
        }

        return $builder;
    }

    /**
     * Add fields to a form builder.
     *
     * @param formBuilderInterface $builder
     *                                      The form builder
     * @param array                $fields
     *                                      The list of fields to add.
     *                                      Each field must be an array with
     *                                      arguments suitable for calling FormBuilderInterface::create or a field
     *                                      created by FormBuilderInterface::create
     *
     * @return formBuilderInterface
     *                              The form builder
     */
    private function addFields(FormBuilderInterface $builder, array $fields)
    {
        foreach ($fields as $field) {
            if (\is_array($field)) {
                $builder->add(\call_user_func_array([$builder, 'create'], $field));
            } elseif ($field instanceof FormBuilderInterface) {
                $builder->add($field);
            }
        }

        return $builder;
    }
}

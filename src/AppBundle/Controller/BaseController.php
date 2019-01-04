<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Yavin\Symfony\Controller\InitControllerInterface;

/**
 * Base controller.
 */
abstract class BaseController extends Controller implements InitControllerInterface
{
    protected $breadcrumbs;

    public function init(Request $request)
    {
        $this->breadcrumbs = $this->get('white_october_breadcrumbs');
        $this->breadcrumbs->addItem('common.forside', $this->get('router')->generate('dashboard_default'));
    }

    public function redirectToReferer(Request $request)
    {
        return $this->redirect($request->headers->get('referer'));
    }

    protected function addCreate(Form $form, $cancelUrl = null)
    {
        return $this->addSubmit($form, 'Create', $cancelUrl, 'Cancel');
    }

    /**
     * Add a submit button and a cancel button to a form.
     *
     * @param form   $form
     *                            The form
     * @param string $cancelUrl
     *                            The cancel url
     * @param mixed  $submitLabel
     * @param mixed  $cancelLabel
     *
     * @return form
     *              The form with the buttons added
     */
    protected function addSubmit(Form $form, $submitLabel, $cancelUrl, $cancelLabel)
    {
        $buttons = [];
        if ($cancelUrl) {
            $buttons['cancel'] = [
                'type' => ButtonType::class,
                'options' => [
                    'label' => $cancelLabel,
                    'button_class' => 'default',
                    'attr' => [
                        'onclick' => 'document.location.href = \''.$cancelUrl.'\'',
                    ],
                ],
            ];
        }
        $buttons['submit'] = [
            'type' => SubmitType::class,
            'options' => [
                'label' => $submitLabel,
            ],
        ];

        $form->add('buttons', FormActionsType::class, [
            'buttons' => $buttons,
        ]);

        return $form;
    }

    protected function addUpdate(Form $form, $cancelUrl = null, $label = 'Update')
    {
        return $this->addSubmit($form, $label, $cancelUrl, 'Cancel');
    }
}

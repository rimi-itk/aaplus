<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Baseline;
use AppBundle\Entity\Bygning;
use AppBundle\Form\Type\BygningSearchType;
use AppBundle\Form\Type\BygningType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yavin\Symfony\Controller\InitControllerInterface;

/**
 * Bygning controller.
 *
 * @Route("/bygning")
 */
class BygningController extends BaseController implements InitControllerInterface
{
    protected $breadcrumbs;

    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('Bygninger', $this->generateUrl('bygning'));
    }

    /**
     * Lists all Bygning entities.
     *
     * @Route(name="bygning", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $bygning = new Bygning();
        $bygning->setStatus(null);
        $form = $this->createSearchForm($bygning);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->breadcrumbs->addItem('Søg', $this->generateUrl('bygning'));
        }

        $em = $this->getDoctrine()->getManager();

        $search = [];

        $search['bygId'] = $bygning->getBygId();
        $search['navn'] = $bygning->getNavn();
        $search['adresse'] = $bygning->getAdresse();
        $search['postnummer'] = $bygning->getPostnummer();
        $search['postBy'] = $bygning->getPostBy();
        $search['segment'] = $bygning->getSegment();
        $search['status'] = $bygning->getStatus();

        $user = $this->getUser();

        $query = $em->getRepository('AppBundle:Bygning')->searchByUser($user, $search);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        return $this->render(
            'AppBundle:Bygning:index.html.twig',
            ['pagination' => $pagination, 'search' => $search, 'form' => $form->createView()]
        );
    }

    /**
     * Creates a new Bygning entity.
     *
     * @Route("/", name="bygning_create", methods={"POST"})
     * @Template("AppBundle:Bygning:new.html.twig")
     *
     * @Security("has_role('ROLE_BYGNING_CREATE')")
     */
    public function createAction(Request $request)
    {
        $entity = new Bygning();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('bygning_show', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new Bygning entity.
     *
     * @Route("/new", name="bygning_new", methods={"GET"})
     * @Template()
     * @Security("has_role('ROLE_BYGNING_CREATE')")
     */
    public function newAction()
    {
        $entity = new Bygning();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Bygning entity.
     *
     * @Route("/{id}", name="bygning_show", methods={"GET"})
     * @Template()
     * @Security("is_granted('BYGNING_VIEW', bygning)")
     */
    public function showAction(Bygning $bygning)
    {
        $deleteForm = $this->createDeleteForm($bygning);

        $this->breadcrumbs->addItem($bygning, $this->generateUrl('bygning'));

        return [
            'entity' => $bygning,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    //---------------- Baseline -------------------//

    /**
     * Displays a form to edit an existing Bygning entity.
     *
     * @Route("/{id}/edit", name="bygning_edit", methods={"GET"})
     * @Template()
     * @Security("is_granted('BYGNING_EDIT', bygning)")
     */
    public function editAction(Bygning $bygning)
    {
        $editForm = $this->createEditForm($bygning);
        $deleteForm = $this->createDeleteForm($bygning);

        $this->breadcrumbs->addItem($bygning, $this->generateUrl('bygning_show', ['id' => $bygning->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('bygning'));

        return [
            'entity' => $bygning,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Edits an existing Bygning entity.
     *
     * @Route("/{id}", name="bygning_update", methods={"PUT"})
     * @Template("AppBundle:Bygning:edit.html.twig")
     * @Security("is_granted('BYGNING_EDIT', bygning)")
     */
    public function updateAction(Request $request, Bygning $bygning)
    {
        $deleteForm = $this->createDeleteForm($bygning);
        $editForm = $this->createEditForm($bygning);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('bygning_show', ['id' => $bygning->getId()]));
        }

        return [
            'entity' => $bygning,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Bygning entity.
     *
     * @Route("/{id}", name="bygning_delete", methods={"DELETE"})
     * @Security("is_granted('BYGNING_EDIT', bygning)")
     */
    public function deleteAction(Request $request, Bygning $bygning)
    {
        $form = $this->createDeleteForm($bygning);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bygning);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('bygning'));
    }

    /**
     * Creates a new Baseline entity.
     *
     * @Route("/{id}/new", name="baseline_create", methods={"POST"})
     * @Template("AppBundle:Baseline:new.html.twig")
     * @Security("is_granted('BYGNING_EDIT', bygning)")
     */
    public function newBaselineAction(Request $request, Bygning $bygning)
    {
        if ($bygning) {
            $baseline = $bygning->getBaseline();
            if (!$baseline) {
                $em = $this->getDoctrine()->getManager();

                $baseline = new Baseline();
                $baseline->setArealdataPrimaerAreal($bygning->getBruttoetageareal());
                $bygning->setBaseline($baseline);

                $em->persist($baseline);
                $em->flush();
            }

            $this->addFlash('success', 'baseline.confirmation.created');

            return $this->redirect($this->generateUrl('baseline_edit', ['id' => $baseline->getId()]));
        }

        throw $this->createNotFoundException('Unable to find Bygning entity.');
    }

    /**
     * Creates a form to search Bygning entities.
     *
     * @param Bygning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm(Bygning $entity)
    {
        $form = $this->createForm(new BygningSearchType(), $entity, [
            'action' => $this->generateUrl('bygning'),
            'method' => 'GET',
        ]);

        return $form;
    }

    /**
     * Creates a form to create a Bygning entity.
     *
     * @param Bygning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Bygning $entity)
    {
        $form = $this->createForm(
            new BygningType($this->getDoctrine(), $this->get('security.authorization_checker')),
            $entity,
            [
                'action' => $this->generateUrl('bygning_create'),
                'method' => 'POST',
            ]
        );

        $this->addCreate($form, $this->generateUrl('bygning'));

        return $form;
    }

    /**
     * Creates a form to delete a Bygning entity by id.
     *
     * @param bygning $bygning
     *                         The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bygning $bygning)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Bygning');
        $message = $repository->getRemoveErrorMessage($bygning);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bygning_delete', ['id' => $bygning->getId()]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', [
                'label' => 'Delete',
                'disabled' => $message,
                'attr' => [
                    'disabled_message' => $message,
                ],
            ])
            ->getForm();
    }

    /**
     * Creates a form to edit a Bygning entity.
     *
     * @param Bygning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Bygning $entity)
    {
        $form = $this->createForm(
            new BygningType($this->getDoctrine(), $this->get('security.authorization_checker')),
            $entity,
            [
                'action' => $this->generateUrl('bygning_update', ['id' => $entity->getId()]),
                'method' => 'PUT',
            ]
        );

        $this->addUpdate($form, $this->generateUrl('bygning_show', ['id' => $entity->getId()]));

        return $form;
    }
}

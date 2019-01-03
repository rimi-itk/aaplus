<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller\BelysningTiltagDetail;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\BelysningTiltagDetail\NytArmatur;
use AppBundle\Form\BelysningTiltagDetail\NytArmaturType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * BelysningTiltagDetail\NytArmatur controller.
 *
 * @Route("/belysningtiltagdetail_nytarmatur")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class NytArmaturController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('nytArmatur.labels.singular', $this->generateUrl('belysningtiltagdetail_nytarmatur'));
    }

    /**
     * Lists all BelysningTiltagDetail\NytArmatur entities.
     *
     * @Route("/", name="belysningtiltagdetail_nytarmatur")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:BelysningTiltagDetail\NytArmatur')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new BelysningTiltagDetail\NytArmatur entity.
     *
     * @Route("/", name="belysningtiltagdetail_nytarmatur_create")
     * @Method("POST")
     * @Template("AppBundle:BelysningTiltagDetail\NytArmatur:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new NytArmatur();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('belysningtiltagdetail_nytarmatur'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new BelysningTiltagDetail\NytArmatur entity.
     *
     * @Route("/new", name="belysningtiltagdetail_nytarmatur_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('belysningtiltagdetail_nytarmatur'));

        $entity = new NytArmatur();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a BelysningTiltagDetail\NytArmatur entity.
     *
     * @Route("/{id}", name="belysningtiltagdetail_nytarmatur_show")
     * @Method("GET")
     * @Template()
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:BelysningTiltagDetail\NytArmatur')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('belysningtiltagdetail_nytarmatur_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BelysningTiltagDetail\NytArmatur entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing BelysningTiltagDetail\NytArmatur entity.
     *
     * @Route("/{id}/edit", name="belysningtiltagdetail_nytarmatur_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(NytArmatur $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('belysningtiltagdetail_nytarmatur_show', ['id' => $entity->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('belysningtiltagdetail_nytarmatur_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BelysningTiltagDetail\NytArmatur entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity->getId());

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Edits an existing BelysningTiltagDetail\NytArmatur entity.
     *
     * @Route("/{id}", name="belysningtiltagdetail_nytarmatur_update")
     * @Method("PUT")
     * @Template("AppBundle:BelysningTiltagDetail\NytArmatur:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:BelysningTiltagDetail\NytArmatur')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BelysningTiltagDetail\NytArmatur entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('belysningtiltagdetail_nytarmatur'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a BelysningTiltagDetail\NytArmatur entity.
     *
     * @Route("/{id}", name="belysningtiltagdetail_nytarmatur_delete")
     * @Method("DELETE")
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:BelysningTiltagDetail\NytArmatur')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BelysningTiltagDetail\NytArmatur entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('belysningtiltagdetail_nytarmatur'));
    }

    /**
     * Creates a form to create a BelysningTiltagDetail\NytArmatur entity.
     *
     * @param NytArmatur $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(NytArmatur $entity)
    {
        $form = $this->createForm(new NytArmaturType(), $entity, [
            'action' => $this->generateUrl('belysningtiltagdetail_nytarmatur_create'),
            'method' => 'POST',
        ]);

        $this->addUpdate($form, $this->generateUrl('belysningtiltagdetail_nytarmatur'));

        return $form;
    }

    /**
     * Creates a form to edit a BelysningTiltagDetail\NytArmatur entity.
     *
     * @param NytArmatur $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(NytArmatur $entity)
    {
        $form = $this->createForm(new NytArmaturType(), $entity, [
            'action' => $this->generateUrl('belysningtiltagdetail_nytarmatur_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('belysningtiltagdetail_nytarmatur_show', ['id' => $entity->getId()]));

        return $form;
    }

    /**
     * Creates a form to delete a BelysningTiltagDetail\NytArmatur entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:BelysningTiltagDetail\NytArmatur');
        $nytarmatur = $repository->find($id);
        $message = $repository->getRemoveErrorMessage($nytarmatur);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('belysningtiltagdetail_nytarmatur_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', [
                'label' => 'Delete',
                'disabled' => $message,
                'attr' => [
                    'disabled_message' => $message,
                ],
            ])
            ->getForm()
        ;
    }
}

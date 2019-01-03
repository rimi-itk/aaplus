<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Pumpe;
use AppBundle\Form\Type\PumpeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Pumpe controller.
 *
 * @Route("/pumpe")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class PumpeController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('pumpe.labels.singular', $this->generateUrl('pumpe'));
    }

    /**
     * Lists all Pumpe entities.
     *
     * @Route("/", name="pumpe")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Pumpe')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Pumpe entity.
     *
     * @Route("/", name="pumpe_create")
     * @Method("POST")
     * @Template("AppBundle:Pumpe:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Pumpe();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pumpe'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new Pumpe entity.
     *
     * @Route("/new", name="pumpe_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('pumpe'));

        $entity = new Pumpe();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Pumpe entity.
     *
     * @Route("/{id}", name="pumpe_show")
     * @Method("GET")
     * @Template()
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Pumpe')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('pumpe_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pumpe entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Pumpe entity.
     *
     * @Route("/{id}/edit", name="pumpe_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Pumpe $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('pumpe_show', ['id' => $entity->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('pumpe_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pumpe entity.');
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
     * Edits an existing Pumpe entity.
     *
     * @Route("/{id}", name="pumpe_update")
     * @Method("PUT")
     * @Template("AppBundle:Pumpe:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Pumpe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pumpe entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('pumpe'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Pumpe entity.
     *
     * @Route("/{id}", name="pumpe_delete")
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
            $entity = $em->getRepository('AppBundle:Pumpe')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pumpe entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('pumpe'));
    }

    /**
     * Creates a form to create a Pumpe entity.
     *
     * @param Pumpe $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Pumpe $entity)
    {
        $form = $this->createForm(new PumpeType(), $entity, [
            'action' => $this->generateUrl('pumpe_create'),
            'method' => 'POST',
        ]);

        $this->addUpdate($form, $this->generateUrl('pumpe'));

        return $form;
    }

    /**
     * Creates a form to edit a Pumpe entity.
     *
     * @param Pumpe $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Pumpe $entity)
    {
        $form = $this->createForm(new PumpeType(), $entity, [
            'action' => $this->generateUrl('pumpe_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('pumpe_show', ['id' => $entity->getId()]));

        return $form;
    }

    /**
     * Creates a form to delete a Pumpe entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Pumpe');
        $pumpe = $repository->find($id);
        $message = $repository->getRemoveErrorMessage($pumpe);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pumpe_delete', ['id' => $id]))
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

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\ELOFordeling;
use AppBundle\Form\ELOFordelingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ELOFordeling controller.
 *
 * @Route("/elofordeling")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class ELOFordelingController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('elofordeling.labels.singular', $this->generateUrl('elofordeling'));
    }

    /**
     * Lists all ELOFordeling entities.
     *
     * @Route("/", name="elofordeling")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:ELOFordeling')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new ELOFordeling entity.
     *
     * @Route("/", name="elofordeling_create")
     * @Method("POST")
     * @Template("AppBundle:ELOFordeling:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ELOFordeling();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('elofordeling'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new ELOFordeling entity.
     *
     * @Route("/new", name="elofordeling_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('elofordeling'));

        $entity = new ELOFordeling();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ELOFordeling entity.
     *
     * @Route("/{id}", name="elofordeling_show")
     * @Method("GET")
     * @Template()
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ELOFordeling')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('elofordeling_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ELOFordeling entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing ELOFordeling entity.
     *
     * @Route("/{id}/edit", name="elofordeling_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(ELOFordeling $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('elofordeling_show', ['id' => $entity->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('elofordeling_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ELOFordeling entity.');
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
     * Edits an existing ELOFordeling entity.
     *
     * @Route("/{id}", name="elofordeling_update")
     * @Method("PUT")
     * @Template("AppBundle:ELOFordeling:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ELOFordeling')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ELOFordeling entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('elofordeling'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a ELOFordeling entity.
     *
     * @Route("/{id}", name="elofordeling_delete")
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
            $entity = $em->getRepository('AppBundle:ELOFordeling')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ELOFordeling entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('elofordeling'));
    }

    /**
     * Creates a form to create a ELOFordeling entity.
     *
     * @param ELOFordeling $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ELOFordeling $entity)
    {
        $form = $this->createForm(new ELOFordelingType(), $entity, [
            'action' => $this->generateUrl('elofordeling_create'),
            'method' => 'POST',
        ]);

        $this->addUpdate($form, $this->generateUrl('elofordeling'));

        return $form;
    }

    /**
     * Creates a form to edit a ELOFordeling entity.
     *
     * @param ELOFordeling $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ELOFordeling $entity)
    {
        $form = $this->createForm(new ELOFordelingType(), $entity, [
            'action' => $this->generateUrl('elofordeling_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('elofordeling_show', ['id' => $entity->getId()]));

        return $form;
    }

    /**
     * Creates a form to delete a ELOFordeling entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('elofordeling_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm()
        ;
    }
}

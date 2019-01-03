<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Regning;
use AppBundle\Form\Type\RegningType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Regning controller.
 *
 * @Route("/regning")
 */
class RegningController extends BaseController
{
    /**
     * Lists all Regning entities.
     *
     * @Route("/", name="regning")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Regning')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Regning entity.
     *
     * @Route("/", name="regning_create")
     * @Method("POST")
     * @Template("AppBundle:Regning:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Regning();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('regning_show', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new Regning entity.
     *
     * @Route("/new", name="regning_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Regning();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Regning entity.
     *
     * @Route("/{id}", name="regning_show")
     * @Method("GET")
     * @Template()
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Regning')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Regning entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Regning entity.
     *
     * @Route("/{id}/edit", name="regning_edit")
     * @Method("GET")
     * @Template()
     *
     * @param mixed $id
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Regning')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Regning entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Edits an existing Regning entity.
     *
     * @Route("/{id}", name="regning_update")
     * @Method("PUT")
     * @Template("AppBundle:Regning:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Regning')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Regning entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('regning_edit', ['id' => $id]));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Regning entity.
     *
     * @Route("/{id}", name="regning_delete")
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
            $entity = $em->getRepository('AppBundle:Regning')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Regning entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('regning'));
    }

    /**
     * Creates a form to create a Regning entity.
     *
     * @param Regning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Regning $entity)
    {
        $form = $this->createForm(new RegningType(), $entity, [
            'action' => $this->generateUrl('regning_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a form to edit a Regning entity.
     *
     * @param Regning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Regning $entity)
    {
        $form = $this->createForm(new RegningType(), $entity, [
            'action' => $this->generateUrl('regning_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Creates a form to delete a Regning entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('regning_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm()
        ;
    }
}

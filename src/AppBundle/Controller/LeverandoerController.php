<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Leverandoer;
use AppBundle\Form\Type\LeverandoerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Leverandoer controller.
 *
 * @Route("/leverandoer")
 */
class LeverandoerController extends BaseController
{
    /**
     * Lists all Leverandoer entities.
     *
     * @Route("/", name="leverandoer", methods={"GET"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Leverandoer')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Leverandoer entity.
     *
     * @Route("/", name="leverandoer_create", methods={"POST"})
     * @Template("AppBundle:Leverandoer:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Leverandoer();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('leverandoer_show', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new Leverandoer entity.
     *
     * @Route("/new", name="leverandoer_new", methods={"GET"})
     * @Template()
     */
    public function newAction()
    {
        $entity = new Leverandoer();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Leverandoer entity.
     *
     * @Route("/{id}", name="leverandoer_show", methods={"GET"})
     * @Template()
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Leverandoer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Leverandoer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Leverandoer entity.
     *
     * @Route("/{id}/edit", name="leverandoer_edit", methods={"GET"})
     * @Template()
     *
     * @param mixed $id
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Leverandoer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Leverandoer entity.');
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
     * Edits an existing Leverandoer entity.
     *
     * @Route("/{id}", name="leverandoer_update", methods={"PUT"})
     * @Template("AppBundle:Leverandoer:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Leverandoer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Leverandoer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('leverandoer_edit', ['id' => $id]));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Leverandoer entity.
     *
     * @Route("/{id}", name="leverandoer_delete", methods={"DELETE"})
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Leverandoer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Leverandoer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('leverandoer'));
    }

    /**
     * Creates a form to create a Leverandoer entity.
     *
     * @param Leverandoer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Leverandoer $entity)
    {
        $form = $this->createForm(new LeverandoerType(), $entity, [
            'action' => $this->generateUrl('leverandoer_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Creates a form to delete a Leverandoer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('leverandoer_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }

    /**
     * Creates a form to edit a Leverandoer entity.
     *
     * @param Leverandoer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Leverandoer $entity)
    {
        $form = $this->createForm(new LeverandoerType(), $entity, [
            'action' => $this->generateUrl('leverandoer_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }
}

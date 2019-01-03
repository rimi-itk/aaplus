<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\ELOKategori;
use AppBundle\Form\ELOKategoriType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ELOKategori controller.
 *
 * @Route("/elokategori")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class ELOKategoriController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('elokategori.labels.singular', $this->generateUrl('elokategori'));
    }

    /**
     * Lists all ELOKategori entities.
     *
     * @Route("/", name="elokategori", methods={"GET"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:ELOKategori')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new ELOKategori entity.
     *
     * @Route("/", name="elokategori_create", methods={"POST"})
     * @Template("AppBundle:ELOKategori:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ELOKategori();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('elokategori'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new ELOKategori entity.
     *
     * @Route("/new", name="elokategori_new", methods={"GET"})
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('elokategori'));

        $entity = new ELOKategori();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ELOKategori entity.
     *
     * @Route("/{id}", name="elokategori_show", methods={"GET"})
     * @Template()
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ELOKategori')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('elokategori_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ELOKategori entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing ELOKategori entity.
     *
     * @Route("/{id}/edit", name="elokategori_edit", methods={"GET"})
     * @Template()
     */
    public function editAction(ELOKategori $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('elokategori_show', ['id' => $entity->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('elokategori_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ELOKategori entity.');
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
     * Edits an existing ELOKategori entity.
     *
     * @Route("/{id}", name="elokategori_update", methods={"PUT"})
     * @Template("AppBundle:ELOKategori:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:ELOKategori')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ELOKategori entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('elokategori'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a ELOKategori entity.
     *
     * @Route("/{id}", name="elokategori_delete", methods={"DELETE"})
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:ELOKategori')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ELOKategori entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('elokategori'));
    }

    /**
     * Creates a form to create a ELOKategori entity.
     *
     * @param ELOKategori $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ELOKategori $entity)
    {
        $form = $this->createForm(new ELOKategoriType(), $entity, [
            'action' => $this->generateUrl('elokategori_create'),
            'method' => 'POST',
        ]);

        $this->addUpdate($form, $this->generateUrl('elokategori'));

        return $form;
    }

    /**
     * Creates a form to delete a ELOKategori entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('elokategori_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }

    /**
     * Creates a form to edit a ELOKategori entity.
     *
     * @param ELOKategori $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ELOKategori $entity)
    {
        $form = $this->createForm(new ELOKategoriType(), $entity, [
            'action' => $this->generateUrl('elokategori_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('elokategori_show', ['id' => $entity->getId()]));

        return $form;
    }
}

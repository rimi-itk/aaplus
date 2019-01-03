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
use AppBundle\Entity\BelysningTiltagDetail\ErstatningsLyskilde;
use AppBundle\Form\BelysningTiltagDetail\ErstatningsLyskildeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * BelysningTiltagDetail\ErstatningsLyskilde controller.
 *
 * @Route("/belysningtiltagdetail_erstatningslyskilde")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class ErstatningsLyskildeController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('erstatningslyskilde.labels.singular', $this->generateUrl('belysningtiltagdetail_erstatningslyskilde'));
    }

    /**
     * Lists all BelysningTiltagDetail\ErstatningsLyskilde entities.
     *
     * @Route("/", name="belysningtiltagdetail_erstatningslyskilde", methods={"GET"})
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:BelysningTiltagDetail\ErstatningsLyskilde')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new BelysningTiltagDetail\ErstatningsLyskilde entity.
     *
     * @Route("/", name="belysningtiltagdetail_erstatningslyskilde_create", methods={"POST"})
     * @Template("AppBundle:BelysningTiltagDetail\ErstatningsLyskilde:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new ErstatningsLyskilde();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('belysningtiltagdetail_erstatningslyskilde'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new BelysningTiltagDetail\ErstatningsLyskilde entity.
     *
     * @Route("/new", name="belysningtiltagdetail_erstatningslyskilde_new", methods={"GET"})
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('belysningtiltagdetail_erstatningslyskilde'));

        $entity = new ErstatningsLyskilde();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a BelysningTiltagDetail\ErstatningsLyskilde entity.
     *
     * @Route("/{id}", name="belysningtiltagdetail_erstatningslyskilde_show", methods={"GET"})
     * @Template()
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:BelysningTiltagDetail\ErstatningsLyskilde')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('belysningtiltagdetail_erstatningslyskilde_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BelysningTiltagDetail\ErstatningsLyskilde entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing BelysningTiltagDetail\ErstatningsLyskilde entity.
     *
     * @Route("/{id}/edit", name="belysningtiltagdetail_erstatningslyskilde_edit", methods={"GET"})
     * @Template()
     */
    public function editAction(ErstatningsLyskilde $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('belysningtiltagdetail_erstatningslyskilde_show', ['id' => $entity->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('belysningtiltagdetail_erstatningslyskilde_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BelysningTiltagDetail\ErstatningsLyskilde entity.');
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
     * Edits an existing BelysningTiltagDetail\ErstatningsLyskilde entity.
     *
     * @Route("/{id}", name="belysningtiltagdetail_erstatningslyskilde_update", methods={"PUT"})
     * @Template("AppBundle:BelysningTiltagDetail\ErstatningsLyskilde:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:BelysningTiltagDetail\ErstatningsLyskilde')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BelysningTiltagDetail\ErstatningsLyskilde entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('belysningtiltagdetail_erstatningslyskilde'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a BelysningTiltagDetail\ErstatningsLyskilde entity.
     *
     * @Route("/{id}", name="belysningtiltagdetail_erstatningslyskilde_delete", methods={"DELETE"})
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:BelysningTiltagDetail\ErstatningsLyskilde')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find BelysningTiltagDetail\ErstatningsLyskilde entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('belysningtiltagdetail_erstatningslyskilde'));
    }

    /**
     * Creates a form to create a BelysningTiltagDetail\ErstatningsLyskilde entity.
     *
     * @param ErstatningsLyskilde $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(ErstatningsLyskilde $entity)
    {
        $form = $this->createForm(new ErstatningsLyskildeType(), $entity, [
            'action' => $this->generateUrl('belysningtiltagdetail_erstatningslyskilde_create'),
            'method' => 'POST',
        ]);

        $this->addUpdate($form, $this->generateUrl('belysningtiltagdetail_erstatningslyskilde'));

        return $form;
    }

    /**
     * Creates a form to edit a BelysningTiltagDetail\ErstatningsLyskilde entity.
     *
     * @param ErstatningsLyskilde $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(ErstatningsLyskilde $entity)
    {
        $form = $this->createForm(new ErstatningsLyskildeType(), $entity, [
            'action' => $this->generateUrl('belysningtiltagdetail_erstatningslyskilde_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('belysningtiltagdetail_erstatningslyskilde_show', ['id' => $entity->getId()]));

        return $form;
    }

    /**
     * Creates a form to delete a BelysningTiltagDetail\ErstatningsLyskilde entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:BelysningTiltagDetail\ErstatningsLyskilde');
        $erstatningslyskilde = $repository->find($id);
        $message = $repository->getRemoveErrorMessage($erstatningslyskilde);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('belysningtiltagdetail_erstatningslyskilde_delete', ['id' => $id]))
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

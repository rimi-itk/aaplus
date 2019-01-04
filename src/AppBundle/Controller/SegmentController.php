<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Segment;
use AppBundle\Form\Type\SegmentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Segment controller.
 *
 * @Route("/segment")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class SegmentController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('segment.labels.singular', $this->generateUrl('segment'));
    }

    /**
     * Lists all Segment entities.
     *
     * @Route("/", name="segment", methods={"GET"})
     * @Template("AppBundle:Segment:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Segment')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Segment entity.
     *
     * @Route("/", name="segment_create", methods={"POST"})
     * @Template("AppBundle:Segment:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Segment();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('segment'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new Segment entity.
     *
     * @Route("/new", name="segment_new", methods={"GET"})
     * @Template("AppBundle:Segment:new.html.twig")
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('segment'));

        $entity = new Segment();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Segment entity.
     *
     * @Route("/{id}", name="segment_show", methods={"GET"})
     * @Template("AppBundle:Segment:show.html.twig")
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Segment')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('segment_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Segment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Segment entity.
     *
     * @Route("/{id}/edit", name="segment_edit", methods={"GET"})
     * @Template("AppBundle:Segment:edit.html.twig")
     */
    public function editAction(Segment $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('segment_show', ['id' => $entity->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('segment_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Segment entity.');
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
     * Edits an existing Segment entity.
     *
     * @Route("/{id}", name="segment_update", methods={"PUT"})
     * @Template("AppBundle:Segment:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Segment')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Segment entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('segment'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Segment entity.
     *
     * @Route("/{id}", name="segment_delete", methods={"DELETE"})
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Segment')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Segment entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('segment'));
    }

    /**
     * Creates a form to create a Segment entity.
     *
     * @param Segment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Segment $entity)
    {
        $form = $this->createForm(SegmentType::class, $entity, [
            'action' => $this->generateUrl('segment_create'),
            'method' => 'POST',
        ]);

        $this->addUpdate($form, $this->generateUrl('segment'));

        return $form;
    }

    /**
     * Creates a form to delete a Segment entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Segment');
        $segment = $repository->find($id);
        $message = $repository->getRemoveErrorMessage($segment);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('segment_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, [
                'label' => 'Delete',
                'disabled' => $message,
                'attr' => [
                    'disabled_message' => $message,
                ],
            ])
            ->getForm();
    }

    /**
     * Creates a form to edit a Segment entity.
     *
     * @param Segment $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Segment $entity)
    {
        $form = $this->createForm(SegmentType::class, $entity, [
            'action' => $this->generateUrl('segment_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('segment_show', ['id' => $entity->getId()]));

        return $form;
    }
}

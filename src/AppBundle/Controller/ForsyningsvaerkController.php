<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Forsyningsvaerk;
use AppBundle\Form\Type\ForsyningsvaerkType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Forsyningsvaerk controller.
 *
 * @Route("/forsyningsvaerk")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class ForsyningsvaerkController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('forsyningsvaerk.labels.plural', $this->generateUrl('forsyningsvaerk'));
    }

    /**
     * Lists all Forsyningsvaerk entities.
     *
     * @Route("/", name="forsyningsvaerk", methods={"GET"})
     * @Template("AppBundle:Forsyningsvaerk:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Forsyningsvaerk')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Forsyningsvaerk entity.
     *
     * @Route("/", name="forsyningsvaerk_create", methods={"POST"})
     * @Template("AppBundle:Forsyningsvaerk:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Forsyningsvaerk();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('forsyningsvaerk'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new Forsyningsvaerk entity.
     *
     * @Route("/new", name="forsyningsvaerk_new", methods={"GET"})
     * @Template("AppBundle:Forsyningsvaerk:new.html.twig")
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('forsyningsvaerk'));

        $entity = new Forsyningsvaerk();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Forsyningsvaerk entity.
     *
     * @Route("/{id}", name="forsyningsvaerk_show", methods={"GET"})
     * @Template("AppBundle:Forsyningsvaerk:show.html.twig")
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Forsyningsvaerk')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('forsyningsvaerk_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Forsyningsvaerk entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Forsyningsvaerk entity.
     *
     * @Route("/{id}/edit", name="forsyningsvaerk_edit", methods={"GET"})
     * @Template("AppBundle:Forsyningsvaerk:edit.html.twig")
     */
    public function editAction(Forsyningsvaerk $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('forsyningsvaerk_show', ['id' => $entity->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('forsyningsvaerk_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Forsyningsvaerk entity.');
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
     * Edits an existing Forsyningsvaerk entity.
     *
     * @Route("/{id}", name="forsyningsvaerk_update", methods={"PUT"})
     * @Template("AppBundle:Forsyningsvaerk:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Forsyningsvaerk')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Forsyningsvaerk entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('forsyningsvaerk'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Forsyningsvaerk entity.
     *
     * @Route("/{id}", name="forsyningsvaerk_delete", methods={"DELETE"})
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Forsyningsvaerk')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Forsyningsvaerk entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('forsyningsvaerk'));
    }

    /**
     * Creates a form to create a Forsyningsvaerk entity.
     *
     * @param Forsyningsvaerk $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Forsyningsvaerk $entity)
    {
        $form = $this->createForm(ForsyningsvaerkType::class, $entity, [
            'action' => $this->generateUrl('forsyningsvaerk_create'),
            'method' => 'POST',
        ]);

        $this->addUpdate($form, $this->generateUrl('forsyningsvaerk'));

        return $form;
    }

    /**
     * Creates a form to edit a Forsyningsvaerk entity.
     *
     * @param Forsyningsvaerk $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Forsyningsvaerk $entity)
    {
        $form = $this->createForm(ForsyningsvaerkType::class, $entity, [
            'action' => $this->generateUrl('forsyningsvaerk_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('forsyningsvaerk_show', ['id' => $entity->getId()]));

        return $form;
    }

    /**
     * Creates a form to delete a Forsyningsvaerk entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Forsyningsvaerk');
        $entity = $repository->find($id);
        $message = $repository->getRemoveErrorMessage($entity);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('forsyningsvaerk_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, [
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

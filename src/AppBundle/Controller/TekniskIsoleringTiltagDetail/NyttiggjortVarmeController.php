<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller\TekniskIsoleringTiltagDetail;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\TekniskIsoleringTiltagDetail\NyttiggjortVarme;
use AppBundle\Form\TekniskIsoleringTiltagDetail\NyttiggjortVarmeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * TekniskIsoleringTiltagDetail\NyttiggjortVarme controller.
 *
 * @Route("/nyttiggjortvarme")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class NyttiggjortVarmeController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('nyttiggjortvarme.labels.singular', $this->generateUrl('nyttiggjortvarme'));
    }

    /**
     * Lists all TekniskIsoleringTiltagDetail\NyttiggjortVarme entities.
     *
     * @Route("/", name="nyttiggjortvarme", methods={"GET"})
     * @Template("AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
     *
     * @Route("/", name="nyttiggjortvarme_create", methods={"POST"})
     * @Template("AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new NyttiggjortVarme();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('nyttiggjortvarme'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
     *
     * @Route("/new", name="nyttiggjortvarme_new", methods={"GET"})
     * @Template("AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme:new.html.twig")
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('nyttiggjortvarme'));

        $entity = new NyttiggjortVarme();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
     *
     * @Route("/{id}", name="nyttiggjortvarme_show", methods={"GET"})
     * @Template("AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme:show.html.twig")
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('nyttiggjortvarme_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
     *
     * @Route("/{id}/edit", name="nyttiggjortvarme_edit", methods={"GET"})
     * @Template("AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme:edit.html.twig")
     */
    public function editAction(NyttiggjortVarme $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('nyttiggjortvarme_show', ['id' => $entity->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('nyttiggjortvarme_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.');
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
     * Edits an existing TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
     *
     * @Route("/{id}", name="nyttiggjortvarme_update", methods={"PUT"})
     * @Template("AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('nyttiggjortvarme'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
     *
     * @Route("/{id}", name="nyttiggjortvarme_delete", methods={"DELETE"})
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('nyttiggjortvarme'));
    }

    /**
     * Creates a form to create a TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
     *
     * @param NyttiggjortVarme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(NyttiggjortVarme $entity)
    {
        $form = $this->createForm(NyttiggjortVarmeType::class, $entity, [
            'action' => $this->generateUrl('nyttiggjortvarme_create'),
            'method' => 'POST',
        ]);

        $this->addUpdate($form, $this->generateUrl('nyttiggjortvarme'));

        return $form;
    }

    /**
     * Creates a form to edit a TekniskIsoleringTiltagDetail\NyttiggjortVarme entity.
     *
     * @param NyttiggjortVarme $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(NyttiggjortVarme $entity)
    {
        $form = $this->createForm(NyttiggjortVarmeType::class, $entity, [
            'action' => $this->generateUrl('nyttiggjortvarme_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('nyttiggjortvarme_show', ['id' => $entity->getId()]));

        return $form;
    }

    /**
     * Creates a form to delete a TekniskIsoleringTiltagDetail\NyttiggjortVarme entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:TekniskIsoleringTiltagDetail\NyttiggjortVarme');
        $nyttiggjortVarme = $repository->find($id);
        $message = $repository->getRemoveErrorMessage($nyttiggjortVarme);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('nyttiggjortvarme_delete', ['id' => $id]))
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

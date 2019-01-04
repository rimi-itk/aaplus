<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Solcelle;
use AppBundle\Form\Type\SolcelleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Solcelle controller.
 *
 * @Route("/solcelle")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class SolcelleController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('solcelle.labels.singular', $this->generateUrl('solcelle'));
    }

    /**
     * Lists all Solcelle entities.
     *
     * @Route("/", name="solcelle", methods={"GET"})
     * @Template("AppBundle:Solcelle:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Solcelle')->findAll();

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Solcelle entity.
     *
     * @Route("/", name="solcelle_create", methods={"POST"})
     * @Template("AppBundle:Solcelle:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Solcelle();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('solcelle'));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Displays a form to create a new Solcelle entity.
     *
     * @Route("/new", name="solcelle_new", methods={"GET"})
     * @Template()
     */
    public function newAction()
    {
        $this->breadcrumbs->addItem('common.add', $this->generateUrl('solcelle'));

        $entity = new Solcelle();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Solcelle entity.
     *
     * @Route("/{id}", name="solcelle_show", methods={"GET"})
     * @Template()
     *
     * @param mixed $id
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Solcelle')->find($id);
        $this->breadcrumbs->addItem($entity, $this->generateUrl('solcelle_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Solcelle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Solcelle entity.
     *
     * @Route("/{id}/edit", name="solcelle_edit", methods={"GET"})
     * @Template()
     */
    public function editAction(Solcelle $entity)
    {
        $this->breadcrumbs->addItem($entity, $this->generateUrl('solcelle_show', ['id' => $entity->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('solcelle_show', ['id' => $entity->getId()]));

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Solcelle entity.');
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
     * Edits an existing Solcelle entity.
     *
     * @Route("/{id}", name="solcelle_update", methods={"PUT"})
     * @Template("AppBundle:Solcelle:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Solcelle')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Solcelle entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('solcelle'));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Solcelle entity.
     *
     * @Route("/{id}", name="solcelle_delete", methods={"DELETE"})
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Solcelle')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Solcelle entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('solcelle'));
    }

    /**
     * Creates a form to create a Solcelle entity.
     *
     * @param Solcelle $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Solcelle $entity)
    {
        $form = $this->createForm(new SolcelleType(), $entity, [
            'action' => $this->generateUrl('solcelle_create'),
            'method' => 'POST',
        ]);

        $this->addUpdate($form, $this->generateUrl('solcelle'));

        return $form;
    }

    /**
     * Creates a form to delete a Solcelle entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Solcelle');
        $solcelle = $repository->find($id);
        $message = $repository->getRemoveErrorMessage($solcelle);

        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solcelle_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', [
                'label' => 'Delete',
                'disabled' => $message,
                'attr' => [
                    'disabled_message' => $message,
                ],
            ])
            ->getForm();
    }

    /**
     * Creates a form to edit a Solcelle entity.
     *
     * @param Solcelle $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Solcelle $entity)
    {
        $form = $this->createForm(new SolcelleType(), $entity, [
            'action' => $this->generateUrl('solcelle_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('solcelle_show', ['id' => $entity->getId()]));

        return $form;
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\BaselineKorrektion;
use AppBundle\Entity\Rapport;
use AppBundle\Form\BaselineKorrektionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * BaselineKorrektion controller.
 *
 * @Route("baselinekorrektion")
 */
class BaselineKorrektionController extends BaseController
{
    public function init(Request $request)
    {
        $this->request = $request;
        parent::init($request);
        $this->breadcrumbs->addItem('Bygninger', $this->generateUrl('bygning'));
    }

    /**
     * Displays a form to edit an existing BaselineKorrektion entity.
     *
     * @Route("/{id}/edit", name="baselinekorrektion_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(BaselineKorrektion $entity)
    {
        $bygning = $entity->getBaseline()->getBygning();
        $rapport = $bygning ? $bygning->getRapport() : null;
        if ($rapport) {
            $this->setRapportBreadcrumbs($rapport, $entity);
        } else {
            $this->breadcrumbs->addItem($entity->getBaseline()->getBygning(), $this->generateUrl('bygning_show', ['id' => $entity->getBaseline()->getBygning()->getId()]));
        }
        $this->breadcrumbs->addItem('baselinekorrektioner.actions.edit');

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BaselineKorrektion entity.');
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
     * Edits an existing BaselineKorrektion entity.
     *
     * @Route("/{id}", name="baselinekorrektion_update")
     * @Method("PUT")
     * @Template("AppBundle:BaselineKorrektion:edit.html.twig")
     *
     * @param mixed $id
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:BaselineKorrektion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BaselineKorrektion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('baseline_show', ['id' => $entity->getBaseline()->getId()]));
        }

        return [
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    ];
    }

    /**
     * Deletes a BaselineKorrektion entity.
     *
     * @Route("/{id}", name="baselinekorrektion_delete")
     * @Method("DELETE")
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:BaselineKorrektion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BaselineKorrektion entity.');
        }

        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('baseline_show', ['id' => $entity->getBaseline()->getId()]));
    }

    /**
     * Use a Rapport as breadcrumbs rather than a Bygning.
     *
     * @param rapport
     *   The rapport
     */
    private function setRapportBreadcrumbs(Rapport $rapport, BaselineKorrektion $korrektion)
    {
        // Reset the breadcrumbs.
        $this->breadcrumbs->clear();
        parent::init($this->request);
        // Add Rapport path.
        $this->breadcrumbs->addItem('Rapporter', $this->generateUrl('rapport'));
        $this->breadcrumbs->addItem($rapport, $this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
        $this->breadcrumbs->addItem('appbundle.bygning.baseline', $this->generateUrl('baseline_edit', ['id' => $korrektion->getBaseline()->getId()]));
    }

    /**
     * Creates a form to edit a BaselineKorrektion entity.
     *
     * @param BaselineKorrektion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(BaselineKorrektion $entity)
    {
        $form = $this->createForm(new BaselineKorrektionType(), $entity, [
      'action' => $this->generateUrl('baselinekorrektion_update', ['id' => $entity->getId()]),
      'method' => 'PUT',
    ]);

        $this->addUpdate($form, $this->generateUrl('baseline_show', ['id' => $entity->getBaseline()->getId()]));

        return $form;
    }

    /**
     * Creates a form to delete a BaselineKorrektion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
      ->setAction($this->generateUrl('baselinekorrektion_delete', ['id' => $id]))
      ->setMethod('DELETE')
      ->add('submit', 'submit', ['label' => 'Delete'])
      ->getForm();
    }
}

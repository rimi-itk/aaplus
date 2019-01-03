<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Energiforsyning;
use AppBundle\Entity\Rapport;
use AppBundle\Form\Type\EnergiforsyningType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Energiforsyning controller.
 *
 * @Route("/rapport/{rapport_id}/energiforsyning")
 * @ParamConverter("rapport", class="AppBundle:Rapport", options={"id" = "rapport_id"})
 */
class EnergiforsyningController extends BaseController
{
    protected $breadcrumbs;

    public function init(Request $request)
    {
        parent::init($request);

        $rapport = $this->getRapport();
        $this->breadcrumbs->addItem('Rapporter', $this->generateUrl('rapport'));
        $this->breadcrumbs->addItem($rapport, $this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
        $this->breadcrumbs->addItem('Energiforsyninger', $this->get('router')->generate('energiforsyning', ['rapport_id' => $this->getRapport()->getId()]));
    }

    /**
     * Lists all Energiforsyning entities.
     *
     * @Route("/", name="energiforsyning", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     */
    public function indexAction()
    {
        $rapport = $this->getRapport();
        $entities = $rapport->getEnergiforsyninger();

        return [
      'entities' => $entities,
      'rapport' => $rapport,
    ];
    }

    /**
     * Displays a form to create a new Energiforsyning entity.
     *
     * @Route("/new", name="energiforsyning_new", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function newAction()
    {
        $entity = (new Energiforsyning())
            ->setRapport($this->getRapport());
        $form = $this->createCreateForm($entity);

        return [
      'entity' => $entity,
      'edit_form' => $form->createView(),
    ];
    }

    /**
     * Finds and displays a Energiforsyning entity.
     *
     * @Route("/{id}", name="energiforsyning_show", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     */
    public function showAction(Rapport $rapport, Energiforsyning $entity)
    {
        $this->breadcrumbs->addItem($entity->__toString());

        return [
      'entity' => $entity,
    ];
    }

    /**
     * Displays a form to edit an existing Energiforsyning entity.
     *
     * @Route("/{id}/edit", name="energiforsyning_edit", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function editAction(Rapport $rapport, Energiforsyning $entity)
    {
        $this->breadcrumbs->addItem($entity->__toString());

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity);

        return [
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    ];
    }

    /**
     * Edits an existing Energiforsyning entity.
     *
     * @Route("/{id}", name="energiforsyning_update", methods={"PUT"})
     * @Template("AppBundle:Energiforsyning:edit.html.twig")
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function updateAction(Request $request, Energiforsyning $entity)
    {
        // @See http://symfony.com/doc/current/cookbook/form/form_collections.html.
        $originalInternProduktioner = $entity->getInternProduktioner()->toArray();

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);

        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($originalInternProduktioner as $internProduktion) {
                if (!$entity->getInternProduktioner()->contains($internProduktion)) {
                    $em->remove($internProduktion);
                }
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('energiforsyning_show', ['rapport_id' => $entity->getRapport()->getId(), 'id' => $entity->getId()]));
        }

        return [
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    ];
    }

    /**
     * Deletes a Energiforsyning entity.
     *
     * @Route("/{id}", name="energiforsyning_delete", methods={"DELETE"})
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function deleteAction(Request $request, Energiforsyning $entity)
    {
        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $em->remove($entity);
                $em->flush();
                $this->flash->success('energiforsyninger.confirmation.deleted');
            } catch (\Exception $e) {
                $this->flash->error('energiforsyninger.error.cannot_delete');
            }
        }

        return $this->redirect($this->generateUrl('energiforsyning', ['rapport_id' => $entity->getRapport()->getId()]));
    }

    /**
     * Creates a new Energiforsyning entity.
     *
     * @Route("/new", name="energiforsyning_create", methods={"POST"})
     * @Template("AppBundle:Energiforsyning:new.html.twig")
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function createAction(Request $request)
    {
        $entity = (new Energiforsyning())
            ->setRapport($this->getRapport());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('energiforsyning_show', ['rapport_id' => $entity->getRapport()->getId(), 'id' => $entity->getId()]));
        }

        return [
      'entity' => $entity,
      'form' => $form->createView(),
    ];
    }

    /**
     * Creates a form to edit a Energiforsyning entity.
     *
     * @param Energiforsyning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Energiforsyning $entity)
    {
        $form = $this->createForm(new EnergiforsyningType(), $entity, [
      'action' => $this->generateUrl('energiforsyning_update', ['rapport_id' => $entity->getRapport()->getId(), 'id' => $entity->getId()]),
      'method' => 'PUT',
    ]);

        $this->addUpdate($form, $this->generateUrl('energiforsyning_show', ['rapport_id' => $entity->getRapport()->getId(), 'id' => $entity->getId()]));

        return $form;
    }

    /**
     * Creates a form to delete a Energiforsyning entity.
     *
     * @param Energiforsyning $entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Energiforsyning $entity)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AppBundle:Energiforsyning');
        $message = $repository->getRemoveErrorMessage($entity);

        return $this->createFormBuilder()
      ->setAction($this->generateUrl('energiforsyning_delete', ['rapport_id' => $entity->getRapport()->getId(), 'id' => $entity->getId()]))
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
     * Creates a form to create a Energiforsyning entity.
     *
     * @param Energiforsyning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Energiforsyning $entity)
    {
        $form = $this->createForm(new EnergiforsyningType(), $entity, [
      'action' => $this->generateUrl('energiforsyning_create', ['rapport_id' => $this->getRapport()->getId()]),
      'method' => 'POST',
    ]);

        $this->addCreate($form, $this->generateUrl('energiforsyning', ['rapport_id' => $this->getRapport()->getId()]));

        return $form;
    }

    /**
     * Get Rapport from request.
     *
     * @return Rapport
     */
    private function getRapport()
    {
        $em = $this->getDoctrine()->getManager();

        $rapport = $em->getRepository('AppBundle:Rapport')->findOneById($this->getRequest()->get('rapport_id'));
        if (!$rapport) {
            throw $this->createNotFoundException('Unable to find Rapport entity.');
        }

        return $rapport;
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\BygningStatusType;
use AppBundle\Entity\Bygning;
use AppBundle\Form\Type\BygningType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Yavin\Symfony\Controller\InitControllerInterface;

/**
 * Bygning controller.
 *
 * @Route("/bygning/{id}/indfoer")
 * @Security("is_granted('ROLE_ADMIN')")
 */
class BygningIndfoerController extends BaseController implements InitControllerInterface
{
    protected $breadcrumbs;

    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('Bygninger', $this->generateUrl('bygning'));
    }

    /**
     * Lists all Bygning entities.
     *
     * @Route("/", name="bygning_indfoer", methods={"GET"})
     * @Template()
     */
    public function indexAction(Bygning $bygning)
    {
        $this->breadcrumbs->addItem($bygning, $this->generateUrl('bygning_show', ['id' => $bygning->getId()]));
        $this->breadcrumbs->addItem('bygninger.actions.indfoer');

        //Set next status to trigger validation group
        $bygning->setStatus(BygningStatusType::DATA_VERIFICERET);

        $editForm = $this->createEditForm($bygning);

        return [
      'entity' => $bygning,
      'edit_form' => $editForm->createView(),
    ];
    }

    /**
     * Edits an existing Bygning entity.
     *
     * @Route("/", name="bygning_indfoer_update", methods={"PUT"})
     * @Template("AppBundle:BygningIndfoer:index.html.twig")
     */
    public function updateAction(Request $request, Bygning $bygning)
    {
        $this->breadcrumbs->addItem($bygning, $this->generateUrl('bygning_show', ['id' => $bygning->getId()]));
        $this->breadcrumbs->addItem('bygninger.actions.indfoer');

        $editForm = $this->createEditForm($bygning);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'bygninger.confirmation.bygning_infoert');

            return $this->redirect($this->generateUrl('bygning_show', ['id' => $bygning->getId()]));
        }

        $this->addFlash('error', 'common.form_error');

        return [
      'entity' => $bygning,
      'edit_form' => $editForm->createView(),
    ];
    }

    /**
     * Creates a form to edit a Bygning entity.
     *
     * @param Bygning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Bygning $entity)
    {
        $form = $this->createForm(new BygningType($this->getDoctrine(), $this->get('security.authorization_checker')), $entity, [
      'action' => $this->generateUrl('bygning_indfoer_update', ['id' => $entity->getId()]),
      'method' => 'PUT',
    ]);

        $this->addUpdate($form, $this->generateUrl('bygning_show', ['id' => $entity->getId()]));

        return $form;
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Configuration;
use AppBundle\Form\Type\ConfigurationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Configuration controller.
 *
 * @Route("/configuration")
 * @Security("has_role('ROLE_SUPER_ADMIN')")
 */
class ConfigurationController extends BaseController
{
    private $configuration;

    /**
     * Lists all Configuration entities.
     *
     * @Route("/", name="configuration", methods={"GET"})
     * @Template("AppBundle:Configuration:index.html.twig")
     */
    public function indexAction()
    {
        $this->breadcrumbs->addItem('configuration.labels.singular', $this->generateUrl('configuration'));

        $entity = $this->getConfiguration();

        return [
      'entity' => $entity,
    ];
    }

    /**
     * Displays a form to edit an existing Configuration entity.
     *
     * @Route("/edit", name="configuration_edit", methods={"GET"})
     * @Template("AppBundle:Configuration:edit.html.twig")
     */
    public function editAction()
    {
        $this->breadcrumbs->addItem('configuration.labels.singular', $this->generateUrl('configuration'));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('configuration_edit'));

        $entity = $this->getConfiguration();

        // @TODO
        if (!$this->container->get('security.context')->isGranted('CONFIGURATION_EDIT', $entity)) {
            throw $this->createAccessDeniedException('You are not allowed to do this');
        }

        $editForm = $this->createEditForm($entity);

        return [
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
    ];
    }

    /**
     * Edits an existing Configuration entity.
     *
     * @Route("/", name="configuration_update", methods={"PUT"})
     * @Template("AppBundle:Configuration:edit.html.twig")
     */
    public function updateAction(Request $request)
    {
        $entity = $this->getConfiguration();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Configuration entity.');
        }

        if (!$this->container->get('security.context')->isGranted('CONFIGURATION_EDIT', $entity)) {
            throw $this->createAccessDeniedException('You are not allowed to do this');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('configuration'));
        }

        return [
      'entity' => $entity,
      'edit_form' => $editForm->createView(),
    ];
    }

    private function getConfiguration()
    {
        if (null === $this->configuration) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('AppBundle:Configuration');
            $this->configuration = $repository->getConfiguration();
        }

        return $this->configuration;
    }

    /**
     * Creates a form to edit a Configuration entity.
     *
     * @param Configuration $configuration The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Configuration $configuration)
    {
        $form = $this->createForm(ConfigurationType::class, $configuration, [
            'action' => $this->generateUrl('configuration_update'),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('configuration'));

        return $form;
    }
}

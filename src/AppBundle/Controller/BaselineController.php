<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Baseline;
use AppBundle\Entity\BaselineKorrektion;
use AppBundle\Entity\Bygning;
use AppBundle\Entity\Rapport;
use AppBundle\Form\BaselineType;
use AppBundle\Form\Type\BaselineKorrektionOverviewType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Baseline controller.
 *
 * @Route("baseline")
 */
class BaselineController extends BaseController
{
    private $request;

    public function init(Request $request)
    {
        $this->request = $request;
        parent::init($request);
        $this->breadcrumbs->addItem('Bygninger', $this->generateUrl('bygning'));
    }

    /**
     * Lists all Baseline entities.
     *
     * @Route("/", name="baseline")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('dashboard_default'));
    }

    /**
     * Finds and displays a Baseline entity.
     *
     * @Route("/{id}", name="baseline_show")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('BASELINE_VIEW', baseline)")
     */
    public function showAction(Baseline $baseline)
    {
        $bygning = $baseline->getBygning();
        $rapport = $bygning ? $bygning->getRapport() : null;
        if ($rapport) {
            $this->setRapportBreadcrumbs($rapport);
        } else {
            $this->breadcrumbs->addItem($baseline->getBygning(), $this->generateUrl('bygning_show', ['id' => $baseline->getBygning()->getId()]));
            $this->breadcrumbs->addItem('appbundle.bygning.baseline', $this->generateUrl('baseline_show', ['id' => $baseline->getId()]));
        }

        if (!$baseline) {
            throw $this->createNotFoundException('Unable to find Baseline entity.');
        }

        $editForm = $this->createOverviewForm($baseline);
        $korrektioner = [];
        foreach ($baseline->getKorrektioner() as $k) {
            $korrektioner[$k->getId()] = $k;
        }

        return [
      'entity' => $baseline,
      'korrektioner' => $korrektioner,
      'edit_form' => $editForm->createView(),
    ];
    }

    /**
     * Edits an existing Baseline entity.
     *
     * @Route("/{id}/overview", name="baseline_overview_update")
     * @Method("PUT")
     * @Template("AppBundle:Baseline:show.html.twig")
     * @Security("is_granted('BASELINE_EDIT', baseline)")
     */
    public function updateOverviewAction(Request $request, Baseline $baseline)
    {
        $editForm = $this->createOverviewForm($baseline);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->flash->success('baseline.confirmation.updated');

            return $this->redirect($this->generateUrl('baseline_show', ['id' => $baseline->getId()]));
        }

        return [
      'entity' => $baseline,
      'edit_form' => $editForm->createView(),
    ];
    }

    /**
     * Displays a form to edit an existing Baseline entity.
     *
     * @Route("/{id}/edit", name="baseline_edit")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('BASELINE_EDIT', baseline)")
     */
    public function editAction(Baseline $baseline)
    {
        $bygning = $baseline->getBygning();
        $rapport = $bygning ? $bygning->getRapport() : null;
        if ($rapport) {
            $this->setRapportBreadcrumbs($rapport);
        } else {
            $this->breadcrumbs->addItem($bygning, $this->generateUrl('bygning_show', ['id' => $bygning->getId()]));
            $this->breadcrumbs->addItem('appbundle.bygning.baseline', $this->generateUrl('baseline_show', ['id' => $baseline->getId()]));
        }
        $this->breadcrumbs->addItem('common.edit');

        if (!$baseline) {
            throw $this->createNotFoundException('Unable to find Baseline entity.');
        }

        $GDNormalAar = '';
        $normtal = $this->container->get('doctrine')->getRepository('AppBundle:GraddageFordeling')->findOneByTitel('Normtal');
        if ($normtal) {
            $GDNormalAar = $normtal->getSumAar();
        }

        $em = $this->getDoctrine()->getManager();
        $graddage = $em->getRepository('AppBundle:GraddageFordeling')->findAll();
        $editForm = $this->createEditForm($baseline);

        return [
      'graddage' => $graddage,
      'entity' => $baseline,
      'edit_form' => $editForm->createView(),
      'graddage_normal' => $GDNormalAar,
    ];
    }

    /**
     * Edits an existing Baseline entity.
     *
     * @Route("/{id}", name="baseline_update")
     * @Method("PUT")
     * @Template("AppBundle:Baseline:edit.html.twig")
     * @Security("is_granted('BASELINE_EDIT', baseline)")
     */
    public function updateAction(Request $request, Baseline $baseline)
    {
        $this->breadcrumbs->addItem($baseline->getBygning(), $this->generateUrl('bygning_show', ['id' => $baseline->getBygning()->getId()]));
        $this->breadcrumbs->addItem('appbundle.bygning.baseline', $this->generateUrl('baseline_show', ['id' => $baseline->getId()]));
        $this->breadcrumbs->addItem('common.edit');

        if (!$baseline) {
            throw $this->createNotFoundException('Unable to find Baseline entity.');
        }

        $editForm = $this->createEditForm($baseline);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            if (!$editForm->get('save_changed')->isClicked()) {
                return $this->redirect($this->generateUrl('baseline_show', ['id' => $baseline->getId()]));
            }
        }

        $GDNormalAar = '';
        $normtal = $this->container->get('doctrine')->getRepository('AppBundle:GraddageFordeling')->findOneByTitel('Normtal');
        if ($normtal) {
            $GDNormalAar = $normtal->getSumAar();
        }

        $em = $this->getDoctrine()->getManager();
        $graddage = $em->getRepository('AppBundle:GraddageFordeling')->findAll();

        return [
      'graddage' => $graddage,
      'entity' => $baseline,
      'edit_form' => $editForm->createView(),
      'graddage_normal' => $GDNormalAar,
    ];
    }

    //---------------- Baseline Korrektion -------------------//

    /**
     * Creates a new BaselineKorrektion entity.
     *
     * @Route("/{id}/new", name="baseline_korrektion_create")
     * @Method("POST")
     * @Template("AppBundle:BaselineKorrektion:new.html.twig")
     * @Security("is_granted('BASELINE_EDIT', baseline)")
     */
    public function newBaselineKorrektionAction(Request $request, Baseline $baseline)
    {
        if ($baseline) {
            $em = $this->getDoctrine()->getManager();

            $baselineKorrektion = new BaselineKorrektion();
            $baselineKorrektion->setBaseline($baseline);

            $em->persist($baselineKorrektion);
            $em->flush();

            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('baselinekorrektioner.confirmation.created');

            return $this->redirect($this->generateUrl('baselinekorrektion_edit', ['id' => $baselineKorrektion->getId()]));
        }

        throw $this->createNotFoundException('Unable to find Baseline entity.');
    }

    /**
     * Use a Rapport as breadcrumbs rather than a Bygning.
     *
     * @param rapport
     *   The rapport
     */
    private function setRapportBreadcrumbs(Rapport $rapport)
    {
        // Reset the breadcrumbs.
        $this->breadcrumbs->clear();
        parent::init($this->request);
        // Add Rapport path.
        $this->breadcrumbs->addItem('Rapporter', $this->generateUrl('rapport'));
        $this->breadcrumbs->addItem($rapport, $this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
        $this->breadcrumbs->addItem('appbundle.bygning.baseline', $this->generateUrl('rapport_edit', ['id' => $rapport->getId()]));
    }

    /**
     * Creates a form to select/deselect TiltagDetail entities.
     *
     * @param BAseline $baseline The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createOverviewForm(Baseline $baseline)
    {
        $form = $this->createForm(new BaselineKorrektionOverviewType(), $baseline, [
      'action' => $this->generateUrl('baseline_overview_update', ['id' => $baseline->getId()]),
      'method' => 'PUT',
    ]);

        $this->addUpdate($form, $this->generateUrl('baseline_show', ['id' => $baseline->getId()]));

        return $form;
    }

    /**
     * Creates a form to edit a Baseline entity.
     *
     * @param Baseline $baseline The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Baseline $baseline)
    {
        $form = $this->createForm(new BaselineType(), $baseline, [
      'action' => $this->generateUrl('baseline_update', ['id' => $baseline->getId()]),
      'method' => 'PUT',
    ]);

        $this->addUpdate($form, $this->generateUrl('baseline_show', ['id' => $baseline->getId()]));

        return $form;
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Regning;
use AppBundle\Entity\Tiltag;
use AppBundle\Entity\TiltagDetail;
use AppBundle\Form\Type\TiltagDatoForDriftType;
use AppBundle\Form\Type\TiltagOverviewDetailType;
use AppBundle\Form\Type\TiltagTilvalgtType;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Tiltag controller.
 *
 * @Route("/tiltag")
 */
class TiltagController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('Rapporter', $this->generateUrl('rapport'));
    }

    /**
     * Lists all Tiltag entities.
     *
     * @Route("/", name="tiltag")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('dashboard_default'));
    }

    /**
     * Finds and displays a Tiltag entity.
     *
     * @Route("/{id}", name="tiltag_show")
     * @Method("GET")
     * @Security("is_granted('TILTAG_VIEW', tiltag)")
     *
     * @param Tiltag $tiltag
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Tiltag $tiltag)
    {
        $this->setBreadcrumb($tiltag);

        $editForm = $this->createOverviewForm($tiltag);
        $details = [];
        foreach ($tiltag->getDetails() as $detail) {
            $details[$detail->getId()] = $detail;
        }

        $template = $this->getTemplate($tiltag, 'show');

        return $this->render($template, [
      'entity' => $tiltag,
      'details' => $details,
      'edit_form' => $editForm->createView(),
    ]);
    }

    /**
     * Displays a form to edit an existing Tiltag entity.
     *
     * @Route("/{id}/edit", name="tiltag_edit")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('TILTAG_EDIT', tiltag)")
     */
    public function editAction(Tiltag $tiltag)
    {
        $this->setBreadcrumb($tiltag);
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('tiltag_edit', ['id' => $tiltag->getId()]));

        $editForm = $this->createEditForm($tiltag);
        $deleteForm = $this->createDeleteForm($tiltag);

        $template = $this->getTemplate($tiltag, 'edit');

        return $this->render($template, [
      'entity' => $tiltag,
      'calculation_warnings' => $tiltag->getCalculationWarnings(),
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    ]);
    }

    /**
     * Edits an existing Tiltag entity.
     *
     * @Route("/{id}", name="tiltag_update")
     * @Method("PUT")
     * @Template("AppBundle:Tiltag:edit.html.twig")
     * @Security("is_granted('TILTAG_EDIT', tiltag)")
     */
    public function updateAction(Request $request, Tiltag $tiltag)
    {
        $deleteForm = $this->createDeleteForm($tiltag);
        $editForm = $this->createEditForm($tiltag);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->flash->success('tiltag.confirmation.updated');

            return $this->redirect($this->generateUrl('tiltag_show', ['id' => $tiltag->getId()]));
        }

        $this->flash->error('tiltag.validation.error');

        return [
      'entity' => $tiltag,
      'edit_form' => $editForm->createView(),
      'delete_form' => $deleteForm->createView(),
    ];
    }

    /**
     * Edits an existing Tiltag entity.
     *
     * @Route("/{id}/overview", name="tiltag_overview_update")
     * @Method("PUT")
     * @Template("AppBundle:Tiltag:show.html.twig")
     * @Security("is_granted('TILTAG_EDIT', tiltag)")
     */
    public function updateOverviewAction(Request $request, Tiltag $tiltag)
    {
        $editForm = $this->createOverviewForm($tiltag);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if ($editForm->get('batch_edit_button')->isClicked()) {
                $this->setBreadcrumb($tiltag);
                $type = strtolower($this->getEntityName($tiltag));
                $this->breadcrumbs->addItem($type.'detail.actions.batch_edit', $this->get('router')->generate('tiltag_detail_batch', ['id' => $tiltag->getId()]));

                $detail = $this->createDetailEntity($tiltag);
                $form = $this->createDetailBatchEditForm($tiltag, $detail);
                $template = $this->getDetailTemplate($detail, 'new');

                return $this->render($template, [
          'isBatchEdit' => true,
          'entity' => $detail,
          'edit_form' => $form->createView(),
        ]);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->flash->success('tiltag.confirmation.updated');

            return $this->redirect($this->generateUrl('tiltag_show', ['id' => $tiltag->getId()]));
        }

        return [
      'entity' => $tiltag,
      'edit_form' => $editForm->createView(),
    ];
    }

    /**
     * Edits "tilvalgt" for an existing Tiltag entity.
     *
     * @Route("/tilvalgt/{id}", name="tiltag_tilvalgt_update")
     * @Method("PUT")
     * @Security("is_granted('TILTAG_EDIT', tiltag)")
     */
    public function updateTilvalgtAction(Request $request, Tiltag $tiltag)
    {
        $editForm = $this->createForm(new TiltagTilvalgtType($tiltag), $tiltag, [
      'action' => $this->generateUrl('tiltag_tilvalgt_update', ['id' => $tiltag->getId()]),
      'method' => 'PUT',
    ]);

        $editForm->handleRequest($request);

        $this->flash->success('tiltag.confirmation.tilfravalgtupdated');

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToReferer($request);
    }

    /**
     * Edits "dato for drift" for an existing Tiltag entity.
     *
     * @Route("/datofordrift/{id}", name="tiltag_dato_for_drift_update")
     * @Method("PUT")
     * @Security("is_granted('TILTAG_EDIT', tiltag)")
     */
    public function updateDatoForDriftAction(Request $request, Tiltag $tiltag)
    {
        $editForm = $this->createForm(new TiltagDatoForDriftType($tiltag), $tiltag, [
      'action' => $this->generateUrl('tiltag_dato_for_drift_update', ['id' => $tiltag->getId()]),
      'method' => 'PUT',
    ]);

        $editForm->handleRequest($request);

        $this->flash->success('tiltag.confirmation.datofordriftupdated');

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToReferer($request);
    }

    /**
     * Deletes a Tiltag entity.
     *
     * @Route("/{id}", name="tiltag_delete")
     * @Method("DELETE")
     * @Security("is_granted('TILTAG_EDIT', tiltag)")
     */
    public function deleteAction(Request $request, Tiltag $tiltag)
    {
        $form = $this->createDeleteForm($tiltag);
        $form->handleRequest($request);

        $rapport = $tiltag->getRapport();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($tiltag);
            $em->flush();

            $this->flash->success('tiltag.confirmation.deleted');
        }

        return $this->redirect($this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
    }

    //---------------- TiltagDetail -------------------//

    /**
     * Displays a form to create a new TiltagDetail entity.
     *
     * @Route("/{id}/detailnew", name="tiltag_detail_new")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('TILTAG_EDIT', tiltag)")
     */
    public function newDetailAction(Tiltag $tiltag)
    {
        $this->setBreadcrumb($tiltag);
        $type = strtolower($this->getEntityName($tiltag));
        $this->breadcrumbs->addItem($type.'detail.actions.add', $this->get('router')->generate('tiltag_detail_new', ['id' => $tiltag->getId()]));

        $detail = $this->createDetailEntity($tiltag);
        $detail->init($tiltag);
        $form = $this->createDetailCreateForm($tiltag, $detail);
        $template = $this->getDetailTemplate($detail, 'new');

        return $this->render($template, [
      'entity' => $detail,
      'edit_form' => $form->createView(),
    ]);
    }

    /**
     * Creates a new Detail entity from form data.
     *
     * @Route("/{id}/detailnew", name="tiltag_detail_create")
     * @Method("POST")
     * @Template()
     * @Security("is_granted('TILTAG_EDIT', tiltag)")
     */
    public function createDetailAction(Request $request, Tiltag $tiltag)
    {
        $detail = $this->createDetailEntity($tiltag);
        $form = $this->createDetailCreateForm($tiltag, $detail);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $detail->setTiltag($tiltag);
            $detail->handleUploads($this->get('stof_doctrine_extensions.uploadable.manager'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($detail);
            $em->flush();

            $this->flash->success('tiltagdetail.confirmation.created');

            return $this->redirect($this->generateUrl('tiltag_show', ['id' => $tiltag->getId()]));
        }

        $template = $this->getDetailTemplate($detail, 'new');

        return $this->render($template, [
      'entity' => $detail,
      'edit_form' => $form->createView(),
    ]);
    }

    /**
     * Updates a batch of details.
     *
     * @Route("/{id}/detailbatch", name="tiltag_detail_batch")
     * @Method("POST")
     * @Template()
     * @Security("is_granted('TILTAG_EDIT', tiltag)")
     */
    public function batchEditDetailAction(Request $request, Tiltag $tiltag)
    {
        // Use createform to validate data
        $formDetail = $this->createDetailEntity($tiltag);
        $formDetail->setBatchEdit(true);
        $form = $this->createDetailBatchEditForm($tiltag, $formDetail);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $detailsId = $form->get('batchEditIdArray')->getData();
        $detailsIdArray = explode(',', $detailsId);

        if ($form->isValid()) {
            $details = $em->getRepository('AppBundle:TiltagDetail')->findById($detailsIdArray);

            foreach ($details as $detail) {
                if ($tiltag->getDetails()->contains($detail)) {
                    $detail->updateProperties($formDetail);
                }
            }

            $em->flush();

            $this->flash->success('tiltagdetail.confirmation.batch_edited');

            return $this->redirect($this->generateUrl('tiltag_show', ['id' => $tiltag->getId()]));
        }

        $this->setBreadcrumb($tiltag);
        $type = strtolower($this->getEntityName($tiltag));
        $this->breadcrumbs->addItem($type.'detail.actions.batch_edit', $this->get('router')->generate('tiltag_detail_batch', ['id' => $tiltag->getId()]));

        $template = $this->getDetailTemplate($formDetail, 'new');

        return $this->render($template, [
      'entity' => $formDetail,
      'edit_form' => $form->createView(),
    ]);
    }

    //---------------- Regning -------------------//

    /**
     * Creates a new Regning entity.
     *
     * @Route("/{id}/regning/new", name="regning_create_x")
     * @Method("POST")
     * @Template("AppBundle:Regning:new.html.twig")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function newRegningAction(Request $request, Tiltag $tiltag)
    {
        $em = $this->getDoctrine()->getManager();
        $regning = new Regning();

        $regning->setTiltag($tiltag);

        $em->persist($regning);
        $em->flush();

        return $this->redirect($this->generateUrl('regning_show', ['id' => $regning->getId()]));
    }

    private function setBreadcrumb(Tiltag $tiltag)
    {
        $this->breadcrumbs->addItem($tiltag->getRapport(), $this->get('router')->generate('rapport_show', ['id' => $tiltag->getRapport()->getId()]));
        $this->breadcrumbs->addItem($tiltag->getTitle(), $this->get('router')->generate('tiltag_show', ['id' => $tiltag->getId()]));
    }

    /**
     * Creates a form to edit a Tiltag entity.
     *
     * @param Tiltag $tiltag The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Tiltag $tiltag)
    {
        $className = $this->getFormTypeClassName($tiltag);
        $form = $this->createForm(new $className($tiltag, $this->get('security.context')), $tiltag, [
      'action' => $this->generateUrl('tiltag_update', ['id' => $tiltag->getId()]),
      'method' => 'PUT',
    ]);

        $this->addUpdate($form, $this->generateUrl('tiltag_show', ['id' => $tiltag->getId()]));

        return $form;
    }

    /**
     * Creates a form to select/deselect TiltagDetail entities.
     *
     * @param Tiltag $tiltag The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createOverviewForm(Tiltag $tiltag)
    {
        $form = $this->createForm(new TiltagOverviewDetailType(), $tiltag, [
      'action' => $this->generateUrl('tiltag_overview_update', ['id' => $tiltag->getId()]),
      'method' => 'PUT',
    ]);

        $this->addUpdate($form, $this->generateUrl('tiltag_show', ['id' => $tiltag->getId()]));

        return $form;
    }

    /**
     * Creates a form to delete a Tiltag entity.
     *
     * @param Tiltag $tiltag
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Tiltag $tiltag)
    {
        return $this->createFormBuilder()
      ->setAction($this->generateUrl('tiltag_delete', ['id' => $tiltag->getId()]))
      ->setMethod('DELETE')
      ->add('submit', 'submit', ['label' => 'Delete'])
      ->getForm();
    }

    /**
     * Get name of an entity.
     *
     * @param object $entity
     *
     * @return string
     */
    private function getEntityName($entity)
    {
        $className = \get_class($entity);
        if (preg_match('@\\\\([^\\\\]+)$@', $className, $matches)) {
            return $matches[1];
        }

        return $className;
    }

    /**
     * Get template for an tiltag and an action.
     * If a specific template for the entity does not exist, a fallback template is returned.
     *
     * @param Tiltag $entity
     * @param string $action
     *
     * @return string
     */
    private function getTemplate(Tiltag $entity, $action)
    {
        $className = $this->getEntityName($entity);
        $template = 'AppBundle:'.$className.':'.$action.'.html.twig';
        if (!$this->get('templating')->exists($template)) {
            $template = 'AppBundle:Tiltag:'.$action.'.html.twig';
        }

        return $template;
    }

    /**
     * Get template for an tiltagdetail and an action.
     * If a specific template for the entity does not exist, a fallback template is returned.
     *
     * @param Tiltag $entity
     * @param string $action
     *
     * @return string
     */
    private function getDetailTemplate(TiltagDetail $entity, $action)
    {
        $className = $this->getEntityName($entity);
        $template = 'AppBundle:'.$className.':'.$action.'.html.twig';
        if (!$this->get('templating')->exists($template)) {
            $template = 'AppBundle:TiltagDetail:'.$action.'.html.twig';
        }

        return $template;
    }

    private function createDetailCreateForm(Tiltag $tiltag, TiltagDetail $detail = null)
    {
        if (!$detail) {
            $detail = $this->createDetailEntity($tiltag);
        }
        $formClass = $this->getFormTypeClassName($detail, true);
        $form = $this->createForm(new $formClass($this->container, $detail), $detail, [
      'action' => $this->generateUrl('tiltag_detail_create', ['id' => $tiltag->getId()]),
      'method' => 'POST',
    ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    //---------------- TiltagDetail Batch Edit -------------------//

    private function createDetailBatchEditForm(Tiltag $tiltag, $detail)
    {
        // Null default values
        $detail->setTilvalgt(null);
        $detail->setIkkeElenaBerettiget(null);
        if (method_exists($detail, 'setIsoleringskappe')) {
            $detail->setIsoleringskappe(null);
        }
        if (method_exists($detail, 'setNyttiggjortVarme')) {
            $detail->setNyttiggjortVarme(null);
        }
        if (method_exists($detail, 'setGlasandel')) {
            $detail->setGlasandel(null);
        }

        $formClass = $this->getFormTypeClassName($detail, true);
        $form = $this->createForm(new $formClass($this->container, $detail, true), $detail, [
      'action' => $this->generateUrl('tiltag_detail_batch', ['id' => $tiltag->getId()]),
      'method' => 'POST',
    ]);

        $batchEditDetailIds = [];
        foreach ($tiltag->getDetails() as $detail) {
            if ($detail->isBatchEdit()) {
                $batchEditDetailIds[] = $detail->getId();
            }
        }

        $implodeIds = empty($batchEditDetailIds) ? '' : implode(',', $batchEditDetailIds);
        $numberOfDetails = empty($batchEditDetailIds) ? 'valgte' : \count($batchEditDetailIds);

        $form->add('batchEditIdArray', 'hidden', ['mapped' => false, 'data' => $implodeIds]);
        $form->add('submit', 'submit', ['label' => 'Opdater '.$numberOfDetails.' tiltag']);

        return $form;
    }

    //---------------- Helpers -------------------//

    /**
     * Get form type class name for a entity.
     *
     * @param Tiltag|TiltagDetail $tiltag
     * @param bool                $isDetail
     *
     * @return string
     */
    private function getFormTypeClassName($tiltag, $isDetail = false)
    {
        $className = '\\AppBundle\\Form\\Type\\'.$this->getEntityName($tiltag).'Type';
        if (!class_exists($className)) {
            $className = '\\AppBundle\\Form\\Type\\Tiltag'.($isDetail ? 'Detail' : '').'Type';
        }

        return $className;
    }

    /**
     * @param Tiltag $tiltag
     *
     * @throws \Exception
     *
     * @return string
     */
    private function getDetailClassName(Tiltag $tiltag)
    {
        $entityName = $this->getEntityName($tiltag);
        $className = '\\AppBundle\\Entity\\'.$entityName.'Detail';
        if (!class_exists($className)) {
            throw new Exception('Cannot find details entity for: '.$entityName);
        }

        return $className;
    }

    /**
     * @param Tiltag $tiltag
     *
     * @throws \Exception
     *
     * @return TiltagDetail
     */
    private function createDetailEntity(Tiltag $tiltag)
    {
        $detailClass = $this->getDetailClassName($tiltag);
        $detail = new $detailClass();

        return $detail;
    }
}

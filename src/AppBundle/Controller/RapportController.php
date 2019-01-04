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
use AppBundle\Entity\Fil;
use AppBundle\Entity\Rapport;
use AppBundle\Form\Type\RapportSearchType;
use AppBundle\Form\Type\RapportShowType;
use AppBundle\Form\Type\RapportStatusType;
use AppBundle\Form\Type\RapportType;
use AppBundle\Form\Type\TiltagDatoForDriftType;
use AppBundle\Form\Type\TiltagTilvalgtType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Rapport controller.
 *
 * @Route("/rapport")
 */
class RapportController extends BaseController
{
    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('Rapporter', $this->generateUrl('rapport'));
    }

    /**
     * Lists all Rapport entities.
     *
     * @Route("/", name="rapport", methods={"GET"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $rapport = new Rapport();
        $rapport->setDatering(null);
        $rapport->setElena(null);
        $rapport->setAva(null);
        $rapport->setVersion(null);
        $bygning = new Bygning();
        $bygning->setStatus(null);
        $rapport->setBygning($bygning);

        $form = $this->createSearchForm($rapport);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->breadcrumbs->addItem('Søg', $this->generateUrl('rapport'));
        }

        $em = $this->getDoctrine()->getManager();

        $search = [];

        $search['navn'] = $rapport->getBygning()->getNavn();
        $search['adresse'] = $rapport->getBygning()->getAdresse();
        $search['postnummer'] = $rapport->getBygning()->getPostnummer();
        $search['segment'] = $rapport->getBygning()->getSegment();
        $search['status'] = $rapport->getBygning()->getStatus();
        $search['datering'] = $rapport->getDatering();
        $search['version'] = $rapport->getVersion();

        $search['elena'] = $rapport->getElena();
        $search['ava'] = $rapport->getAva();

        $user = $this->getUser();

        $query = $em->getRepository('AppBundle:Rapport')->searchByUser($user, $search);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1),
            20
        );

        return $this->render(
            'AppBundle:Rapport:index.html.twig',
            ['pagination' => $pagination, 'search' => $search, 'form' => $form->createView()]
        );
    }

    /**
     * Finds and displays a Rapport entity.
     *
     * @Route("/{id}", name="rapport_show", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     *
     * @param Rapport $rapport
     *
     * @return array
     */
    public function showAction(Rapport $rapport)
    {
        $this->breadcrumbs->addItem($rapport, $this->generateUrl('rapport_show', ['id' => $rapport->getId()]));

        $deleteForm = $this->createDeleteForm($rapport->getId())->createView();
        $editForm = $this->createEditFormFinansiering($rapport);

        $status = $rapport->getBygning()->getStatus();

        // Bygning Status forms
        $formArray = [];
        if (BygningStatusType::TILKNYTTET_RAADGIVER === $status) {
            $formArray['next_status_form'] = $this->createStatusForm(
                $rapport,
                'rapport_submit',
                'rapporter.actions.submit'
            )->createView();
        } elseif (BygningStatusType::AFLEVERET_RAADGIVER === $status) {
            $formArray['prev_status_form'] = $this->createStatusForm(
                $rapport,
                'rapport_retur',
                'rapporter.actions.retur'
            )->createView();
            $formArray['next_status_form'] = $this->createStatusForm(
                $rapport,
                'rapport_verify',
                'rapporter.actions.verify'
            )->createView();
        } elseif (BygningStatusType::AAPLUS_VERIFICERET === $status) {
            $formArray['next_status_form'] = $this->createStatusForm(
                $rapport,
                'rapport_approve',
                'rapporter.actions.approve'
            )->createView();
        } elseif (BygningStatusType::GODKENDT_AF_MAGISTRAT === $status) {
            $formArray['next_status_form'] = $this->createStatusForm(
                $rapport,
                'rapport_implementation',
                'rapporter.actions.implementation'
            )->createView();
        } elseif (BygningStatusType::UNDER_UDFOERSEL === $status) {
            $formArray['next_status_form'] = $this->createStatusForm(
                $rapport,
                'rapport_operation',
                'rapporter.actions.operation'
            )->createView();
        }

        // Tiltag tilvalgt/fravalgt forms
        $tilvalgtFormArray = [];
        $fravalgtFormArray = [];

        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            foreach ($rapport->getTiltag() as $tiltag) {
                if ($tiltag->getTilvalgt()) {
                    $tilvalgtFormArray[$tiltag->getId()] = $this->createEditTilvalgTilvalgtForm(
                        $tiltag,
                        $rapport
                    )->createView();
                } else {
                    $fravalgtFormArray[$tiltag->getId()] = $this->createEditTilvalgTilvalgtForm(
                        $tiltag,
                        $rapport
                    )->createView();
                }
            }
        }

        // Dato for drift forms
        $tiltagDatoForDriftFormArray = [];
        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            if (BygningStatusType::UNDER_UDFOERSEL === $status || BygningStatusType::DRIFT === $status) {
                foreach ($rapport->getTiltag() as $tiltag) {
                    if ($tiltag->getTilvalgt()) {
                        $tiltagDatoForDriftFormArray[$tiltag->getId()] = $this->createEditTiltagDatoForDriftForm(
                            $tiltag,
                            $rapport
                        )->createView();
                    }
                }
            }
        }

        $calculationChanges = $this->container->get('aaplus.rapport_calculation')->getChanges($rapport);
        $calculateForm = $this->createCalculateForm($rapport, $calculationChanges)->createView();

        $twigVars = [
            'entity' => $rapport,
            'dato_for_drift_form_array' => $tiltagDatoForDriftFormArray,
            'tilvalgt_form_array' => $tilvalgtFormArray,
            'fravalgt_form_array' => $fravalgtFormArray,
            'delete_form' => $deleteForm,
            'edit_form' => $editForm ? $editForm->createView() : null,
            'calculate_form' => $calculateForm,
            'calculation_changes' => $calculationChanges,
            'calculation_warnings' => $rapport->getCalculationWarnings(),
        ];

        return array_merge($twigVars, $formArray);
    }

    /**
     * Finds and displays Baseline for a Rapport entity.
     *
     * @Route("/{id}/baseline", name="rapport_show_baseline", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     *
     * @param Rapport $rapport
     *
     * @return array
     */
    public function baselineAction(Rapport $rapport)
    {
        $this->breadcrumbs->addItem($rapport, $this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
        $this->breadcrumbs->addItem(
            'rapporter.actions.edit',
            $this->generateUrl('rapport_edit', ['id' => $rapport->getId()])
        );

        $showForm = $this->createForm(new RapportShowType($this->get('security.context'), $rapport), $rapport, [
            'action' => '#',
            'method' => 'PUT',
        ]);

        return [
            'entity' => $rapport,
            'show_form' => $showForm->createView(),
        ];
    }

    //---------------- Rådgiver aflever -------------------//

    /**
     * Finds and displays a Rapport entity in PDF form. (Resultatoversigt).
     *
     * @Route("/{id}/pdf2", name="rapport_show_pdf2", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     *
     * @param Rapport $rapport
     *
     * @return array
     */
    public function showPdf2Action(Rapport $rapport)
    {
        $exporter = $this->get('aaplus.pdf_export');
        $pdf = $exporter->export2($rapport);

        $pdfName = $rapport->getBygning()->getAdresse().'-Dokument 2-'.date('Y-m-d').'-Status '.$rapport->getBygning()->getNummericStatus().'-Itt '.$rapport->getVersion();

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$pdfName.'.pdf"',
        ]);
    }

    //---------------- Retur til Rådgiver -------------------//

    /**
     * Finds and displays a Rapport entity in PDF form. (Detailark).
     *
     * @Route("/{id}/pdf5", name="rapport_show_pdf5", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     *
     * @param Rapport $rapport
     *
     * @return array
     */
    public function showPdf5Action(Rapport $rapport)
    {
        $exporter = $this->get('aaplus.pdf_export');
        $pdf = $exporter->export5($rapport);

        $pdfName = $rapport->getBygning()->getAdresse().'-Dokument 5-'.date('Y-m-d').'-Status '.$rapport->getBygning()->getNummericStatus().'-Itt '.$rapport->getVersion();

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$pdfName.'.pdf"',
        ]);
    }

    //---------------- Aa+ Verificeret -------------------//

    /**
     * Finds and displays a Rapport entity.
     *
     * @Route("/{id}/pdf2test", name="rapport_show_pdf2test", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     *
     * @param Rapport $rapport
     *
     * @return array
     */
    public function showPdf2TestAction(Rapport $rapport)
    {
        return [
            'rapport' => $rapport,
            'entity' => $rapport,
        ];
    }

    //---------------- Godkendt Magistrat -------------------//

    /**
     * Finds and displays a Rapport entity.
     *
     * @Route("/{id}/pdf5test", name="rapport_show_pdf5test", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     *
     * @param Rapport $rapport
     *
     * @return array
     */
    public function showPdf5TestAction(Rapport $rapport)
    {
        return [
            'rapport' => $rapport,
            'entity' => $rapport,
        ];
    }

    //---------------- Under udførsel -------------------//

    /**
     * Displays a form to edit an existing Rapport entity.
     *
     * @Route("/{id}/edit", name="rapport_edit", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function editAction(Rapport $rapport)
    {
        $this->breadcrumbs->addItem($rapport, $this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('rapport_edit', ['id' => $rapport->getId()]));

        $editForm = $this->createEditForm($rapport);
        $deleteForm = $this->createDeleteForm($rapport->getId());

        return [
            'entity' => $rapport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    //---------------- Tiltag -------------------//

    /**
     * Edits an existing Rapport entity.
     *
     * @Route("/{id}", name="rapport_update", methods={"PUT"})
     * @Template("AppBundle:Rapport:edit.html.twig")
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function updateAction(Request $request, Rapport $rapport)
    {
        $this->breadcrumbs->addItem($rapport, $this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
        $this->breadcrumbs->addItem('common.edit', $this->generateUrl('rapport_edit', ['id' => $rapport->getId()]));

        $deleteForm = $this->createDeleteForm($rapport->getId());
        $editForm = $this->createEditForm($rapport);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
        }

        return [
            'entity' => $rapport,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    //---------------- Regninger -------------------//

    /**
     * Deletes a Rapport entity.
     *
     * @Route("/{id}", name="rapport_delete", methods={"DELETE"})
     *
     * @param mixed $id
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Rapport')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rapport entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rapport'));
    }

    /**
     * Aaplus verifies a Rapport entity.
     *
     * @Route("/{id}/submit", name="rapport_submit", methods={"PUT"})
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function submitAction(Request $request, Rapport $rapport)
    {
        $this->statusAction(
            $request,
            $rapport,
            BygningStatusType::AFLEVERET_RAADGIVER,
            'rapport_submit',
            'rapporter.actions.submit'
        );

        $this->addFlash('success', 'rapporter.confirmation.submitted');

        return $this->redirect($this->generateUrl('dashboard_default'));
    }

    /**
     * Aaplus verifies a Rapport entity.
     *
     * @Route("/{id}/retur", name="rapport_retur", methods={"PUT"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function returAction(Request $request, Rapport $rapport)
    {
        $this->statusAction(
            $request,
            $rapport,
            BygningStatusType::TILKNYTTET_RAADGIVER,
            'rapport_retur',
            'rapporter.actions.retur'
        );

        $this->addFlash('success', 'rapporter.confirmation.retur');

        return $this->redirect($this->generateUrl('dashboard_default'));
    }

    /**
     * Aaplus verifies a Rapport entity.
     *
     * @Route("/{id}/verify", name="rapport_verify", methods={"PUT"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function verifyAction(Request $request, Rapport $rapport)
    {
        $this->statusAction(
            $request,
            $rapport,
            BygningStatusType::AAPLUS_VERIFICERET,
            'rapport_verify',
            'rapporter.actions.verify'
        );

        $this->addFlash('success', 'rapporter.confirmation.verified');

        return $this->redirect($this->generateUrl('dashboard_default'));
    }

    /**
     * Magistrat Godkendt.
     *
     * @Route("/{id}/approve", name="rapport_approve", methods={"PUT"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function approvedAction(Request $request, Rapport $rapport)
    {
        $this->statusAction(
            $request,
            $rapport,
            BygningStatusType::GODKENDT_AF_MAGISTRAT,
            'rapport_approve',
            'rapporter.actions.approve'
        );

        $this->addFlash('success', 'rapporter.confirmation.approved');

        return $this->redirect($this->generateUrl('dashboard_default'));
    }

    /**
     * Under udførsel.
     *
     * @Route("/{id}/implementation", name="rapport_implementation", methods={"PUT"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function implementationAction(Request $request, Rapport $rapport)
    {
        $this->statusAction(
            $request,
            $rapport,
            BygningStatusType::UNDER_UDFOERSEL,
            'rapport_implementation',
            'rapporter.actions.implementation'
        );

        $this->addFlash('success', 'rapporter.confirmation.implementation');

        return $this->redirect($this->generateUrl('dashboard_default'));
    }

    /**
     * Drift.
     *
     * @Route("/{id}/operation", name="rapport_operation", methods={"PUT"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function operationAction(Request $request, Rapport $rapport)
    {
        $this->statusAction(
            $request,
            $rapport,
            BygningStatusType::DRIFT,
            'rapport_operation',
            'rapporter.actions.operation'
        );

        $this->addFlash('success', 'rapporter.confirmation.operation');

        return $this->redirect($this->generateUrl('dashboard_default'));
    }

    /**
     * Creates a new Tiltag entity.
     *
     * @Route("/{id}/tiltag/new/{type}", name="tiltag_create", methods={"POST"})
     * @Template("AppBundle:Tiltag:new.html.twig")
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     *
     * @param mixed $type
     */
    public function newTiltagAction(Request $request, Rapport $rapport, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $tiltag = $em->getRepository('AppBundle:Tiltag')->create($type);

        $tiltag->setRapport($rapport);

        $em->persist($tiltag);
        $em->flush();

        $this->addFlash('success', $type.'tiltag.confirmation.created');

        return $this->redirect($this->generateUrl('tiltag_edit', ['id' => $tiltag->getId()]));
    }

    /**
     * Finds and displays a Rapport entity.
     *
     * @Route("/{id}/regninger", name="rapport_regninger_show", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     *
     * @param Rapport $rapport
     *
     * @return array
     */
    public function showRegningerAction(Rapport $rapport)
    {
        $this->breadcrumbs->addItem($rapport->getBygning(), $this->get('router')
            ->generate('bygning_show', [
                'id' => $rapport->getBygning()
                    ->getId(),
            ]));
        $this->breadcrumbs->addItem($rapport->getVersion(), $this->get('router')
            ->generate('rapport_show', ['id' => $rapport->getId()]));

        $deleteForm = $this->createDeleteForm($rapport->getId());

        return [
            'tiltag' => $rapport->getTiltag(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Finds and displays a Rapport entity.
     *
     * @Route("/{id}/finansiering", name="rapport_finansiering_show", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     *
     * @param Rapport $rapport
     *
     * @return array
     */
    public function showFinansieringAction(Rapport $rapport)
    {
        $this->breadcrumbs->addItem($rapport->getBygning(), $this->get('router')
            ->generate('bygning_show', [
                'id' => $rapport->getBygning()
                    ->getId(),
            ]));
        $this->breadcrumbs->addItem($rapport->getVersion(), $this->get('router')
            ->generate('rapport_show', ['id' => $rapport->getId()]));

        $editForm = $this->createEditFormFinansiering($rapport);

        return [
            'entity' => $rapport,
            'edit_form' => $editForm ? $editForm->createView() : null,
        ];
    }

    /**
     * Edits an existing Rapport entity.
     *
     * @Route("/{id}/finansiering", name="rapport_finansiering_update", methods={"PUT"})
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function updateActionFinansiering(Request $request, Rapport $rapport)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditFormFinansiering($rapport);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $rapport->calculate();
            $em->persist($rapport);
            $em->flush();

            return $this->redirect($request->headers->get('referer'));
        }

        return [
            'entity' => $rapport,
            'edit_form' => $editForm->createView(),
        ];
    }

    //---------------- Generic Status -------------------//

    /**
     * Calculates and persists a Rapport entity.
     *
     * @Route("/{id}/calculate", name="rapport_calculate", methods={"POST"})
     * @Security("is_granted('RAPPORT_EDIT', rapport)")
     */
    public function calculateAction(Request $request, Rapport $rapport)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            foreach ($rapport->getTiltag() as $tiltag) {
                foreach ($tiltag->getDetails() as $detail) {
                    $detail->calculate();
                    $em->persist($detail);
                }
                $tiltag->calculate();
                $em->persist($tiltag);
            }
            $rapport->calculate();

            $em->persist($rapport);
            $em->flush();

            $this->addFlash('success', 'Rapport calculated');
        } catch (\Exception $ex) {
            $this->addFlash('error', 'Cannot calculate rapport');
        }

        return $this->redirect($this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
    }

    /**
     * Lists all files for the Rapport.
     *
     * @Route("/{id}/filer", name="rapport_filer", methods={"GET"})
     * @Template()
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     */
    public function showFilerAction(Request $request, Rapport $rapport)
    {
        $this->breadcrumbs->addItem($rapport, $this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
        $this->breadcrumbs->addItem('filer', $this->generateUrl('rapport_filer', ['id' => $rapport->getId()]));

        $em = $this->getDoctrine()->getManager();
        $filRepository = $em->getRepository('AppBundle:Fil');

        $filer = $filRepository->findByEntity($rapport);

        return [
            'entity' => $rapport,
            'filer' => $filer,
        ];
    }

    /**
     * Lists all files for the Rapport.
     *
     * @Route("/{id}/fil/{fil}", name="rapport_fil", methods={"GET"})
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     */
    public function filAction(Request $request, Rapport $rapport, Fil $fil)
    {
        $path = $fil->getFilepath();
        $file = new File($path);
        $response = new BinaryFileResponse($file->getRealPath());
        if ($request->query->getBoolean('download', false)) {
            $response->setContentDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $fil->getNavn()
            );
        }

        return $response;
    }

    /**
     * Download all files for Rapport.
     *
     * @Route("/{id}/download_files", name="rapport_download_files", methods={"GET"})
     * @Security("is_granted('RAPPORT_VIEW', rapport)")
     */
    public function downloadFilesAction(Request $request, Rapport $rapport)
    {
        $allFiles = $rapport->getAllFiles();

        if (!$allFiles) {
            $this->addFlash('error', 'rapporter.messages.no_files');

            return $this->redirect($this->generateUrl('rapport_show', ['id' => $rapport->getId()]));
        }

        $zipName = 'Bilag-'.$rapport->getBygning()->getAdresse().'-'.date('Y-m-d').'.zip';
        // Sanitize filename.
        $zipName = preg_replace('/[^a-z0-9.-]/i', '_', $zipName);

        $archive = new \ZipArchive();
        $zipPath = tempnam(sys_get_temp_dir(), $zipName);
        $archive->open($zipPath, \ZipArchive::CREATE);

        $this->addFilesToArchive($archive, $allFiles);
        $archive->close();

        $response = new BinaryFileResponse($zipPath);
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $zipName
        );

        return $response;
    }

    /**
     * Creates a form to search Rapport entities.
     *
     * @param Bygning $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm(Rapport $entity)
    {
        // @TODO $form = $this->createForm(new RapportSearchType($this->get('security.context')), $entity, [
        $form = $this->createForm(RapportSearchType::class, $entity, [
            'action' => $this->generateUrl('rapport'),
            'method' => 'GET',
        ]);

        return $form;
    }

    /**
     * Creates a form to delete a Rapport entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rapport_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }

    /**
     * Creates a form to edit a Rapport entity.
     *
     * @param Rapport $rapport The rapport
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditFormFinansiering(Rapport $rapport)
    {
        if (!$this->container->get('security.context')->isGranted('RAPPORT_EDIT', $rapport)) {
            return null;
        }

        $form = $this->createFormBuilder($rapport)
            ->setAction($this->generateUrl('rapport_finansiering_update', ['id' => $rapport->getId()]))
            ->setMethod('PUT')
            ->add('laanLoebetid', null, [
                'label' => 'løbetid',
                'attr' => [
                    'input_group' => [
                        'append' => 'år',
                    ],
                ],
            ])
            ->getForm();

        $this->addUpdate($form);

        return $form;
    }

    /**
     * Creates a form to verify a Rapport entity by id.
     *
     * @param mixed $id    The entity id
     * @param mixed $route
     * @param mixed $label
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createStatusForm(Rapport $entity, $route, $label)
    {
        $status = $entity->getBygning()->getStatus();

        $form = $this->createForm(new RapportStatusType($this->get('security.context'), $status), $entity, [
            'action' => $this->generateUrl($route, ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, null, $label);

        return $form;
    }

    /**
     * Creates a form to edit a Tiltag entity.
     *
     * @param Tiltag $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditTilvalgTilvalgtForm($entity, Rapport $rapport)
    {
        $form = $this->createForm(new TiltagTilvalgtType($entity), $entity, [
            'action' => $this->generateUrl('tiltag_tilvalgt_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form);
        //$form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a form to edit a Tiltag entity.
     *
     * @param Tiltag $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditTiltagDatoForDriftForm($entity)
    {
        $form = $this->createForm(new TiltagDatoForDriftType($entity), $entity, [
            'action' => $this->generateUrl('tiltag_dato_for_drift_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form);

        return $form;
    }

    /**
     * Creates a form to calculate a Rapport entity.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCalculateForm(Rapport $rapport, array $changes)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rapport_calculate', ['id' => $rapport->getId()]))
            ->setMethod('POST')
            ->add('submit', 'submit', [
                'label' => 'rapporter.actions.re-calculate',
                'disabled' => empty($changes),
                'button_class' => 'default',
            ])
            ->getForm();
    }

    //---------------- Drift -------------------//

    /**
     * Creates a form to edit a Rapport entity.
     *
     * @param Rapport $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Rapport $entity)
    {
        $form = $this->createForm(new RapportType($this->get('security.context'), $entity), $entity, [
            'action' => $this->generateUrl('rapport_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $this->addUpdate($form, $this->generateUrl('rapport_show', ['id' => $entity->getId()]));

        return $form;
    }

    private function statusAction(Request $request, Rapport $rapport, $status, $route, $label)
    {
        $form = $this->createStatusForm($rapport, $route, $label);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $rapport = $em->getRepository('AppBundle:Rapport')->find($rapport->getId());

            if (!$rapport) {
                throw $this->createNotFoundException('Unable to find Rapport entity.');
            }

            $exporter = $this->get('aaplus.pdf_export');
            $filRepository = $em->getRepository('AppBundle:Fil');

            $pdf = $exporter->export2($rapport);
            $pdfName = $rapport->getBygning()->getAdresse().'-Dokument 2-'.date('Y-m-d').'-Status '.$rapport->getBygning()->getNummericStatus().'-Itt '.$rapport->getVersion().'.pdf';

            $fil = new Fil();
            $fil
                ->setNavn($pdfName)
                ->setEntity($rapport);
            $filRepository->saveContent($pdf, $fil, $this->container);
            $em->persist($fil);

            $pdf = $exporter->export5($rapport);
            $pdfName = $rapport->getBygning()->getAdresse().'-Dokument 5-'.date('Y-m-d').'-Status '.$rapport->getBygning()->getNummericStatus().'-Itt '.$rapport->getVersion().'.pdf';

            $fil = new Fil();
            $fil
                ->setNavn($pdfName)
                ->setEntity($rapport);
            $filRepository->saveContent($pdf, $fil, $this->container);
            $em->persist($fil);

            $rapport->getBygning()->setStatus($status);
            $rapport->setVersion($rapport->getVersion() + 1);
            $em->flush();
        }
    }

    /**
     * Add files to archive.
     *
     * If a value in $files is an array the files in the value will be added to a sub-directory.
     *
     * @param array  $files
     *                      The files to add
     * @param string $dir
     *                      The dir to add files to. Must be empty or end with a slash (/).
     */
    private function addFilesToArchive(\ZipArchive $archive, array $files, $dir = '')
    {
        if ($files) {
            foreach ($files as $key => $data) {
                if (\is_array($data)) {
                    $subDir = $dir.$key.'/';
                    $archive->addEmptyDir($subDir);
                    $this->addFilesToArchive($archive, $data, $subDir);
                } else {
                    $file = new File($data);
                    $archive->addFromString($dir.$file->getBasename(), file_get_contents($file->getRealPath()));
                }
            }
        }
    }
}

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
use AppBundle\Entity\User;
use AppBundle\Form\Type\BygningDashboardType;
use Doctrine\ORM\Query;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DashboardController.
 */
class DashboardController extends BaseController
{
    /**
     * @TODO: Missing description.
     *
     * @Route("/", name="dashboard_default")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_EDIT')) {
            // Aa+
            if ($user->hasGroup('Aa+')) {
                return $this->dashboardView($request, $user, 'aaplusAnsvarlig');
            }

            // Rådgiver
            if ($user->hasGroup('Rådgiver')) {
                return $this->dashboardView($request, $user, 'igangvaerende');
            }

            // Projekterende
            if ($user->hasGroup('Projekterende')) {
                return $this->dashboardView($request, $user, 'projekterende');
            }

            // Projektleder
            if ($user->hasGroup('Projektleder')) {
                return $this->dashboardView($request, $user, 'projektleder');
            }
        }

        return $this->dashboardView($request, $user, 'default');
    }

    /**
     * @TODO: Missing description.
     *
     * @Route("/bygninger", name="dashboard_aaplusAnsvarlig")
     * @Template()
     */
    public function indexAaplusAnsvarligAction(Request $request)
    {
        $user = $this->getUser();
        $paginator = $this->get('knp_paginator');

        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->dashboardView($request, $user, 'aaplusAnsvarlig');
        }

        return $this->redirectToRoute('dashboard_default');
    }

    /**
     * @TODO: Missing description.
     *
     * @Route("/segmenter", name="dashboard_segmenter")
     * @Template()
     */
    public function indexSegmenterAction(Request $request)
    {
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN') && !$user->getSegmenter()->isEmpty()) {
            return $this->dashboardView($request, $user, 'segmenter');
        }

        return $this->redirectToRoute('dashboard_default');
    }

    /**
     * @TODO: Missing description.
     *
     * @Route("/projektleder", name="dashboard_projektleder")
     * @Template()
     */
    public function indexProjektlederAction(Request $request)
    {
        $user = $this->getUser();

        if ($user->hasGroup('Projektleder')) {
            return $this->dashboardView($request, $user, 'projektleder');
        }

        return $this->redirectToRoute('dashboard_default');
    }

    /**
     * @TODO: Missing description.
     *
     * @Route("/projekterende", name="dashboard_projekterende")
     * @Template()
     */
    public function indexProjekterendeAction(Request $request)
    {
        $user = $this->getUser();

        if ($user->hasGroup('Projekterende')) {
            return $this->dashboardView($request, $user, 'projekterende');
        }

        return $this->redirectToRoute('dashboard_default');
    }

    /**
     * @TODO: Missing description.
     *
     * @Route("/igangvaerende", name="dashboard_igangvaerende")
     * @Template()
     */
    public function indexIgangvaerendeAction(Request $request)
    {
        $user = $this->getUser();

        if ($user->hasGroup('Rådgiver')) {
            return $this->dashboardView($request, $user, 'igangvaerende');
        }

        return $this->redirectToRoute('dashboard_default');
    }

    /**
     * @TODO: Missing description.
     *
     * @Route("/indsendt", name="dashboard_indsendt")
     * @Template()
     */
    public function indexIndsendtAction(Request $request)
    {
        $user = $this->getUser();

        if ($user->hasGroup('Rådgiver')) {
            return $this->dashboardView($request, $user, 'indsendt');
        }

        return $this->redirectToRoute('dashboard_default');
    }

    /**
     * @param Request $request
     * @param User    $user
     * @param $filterCondition
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function dashboardView(Request $request, User $user, $filterCondition)
    {
        $paginator = $this->get('knp_paginator');

        // initialize a query builder
        $filterBuilder = $this->getDashboardFilterBuilder($user, $filterCondition);

// @TODO        $form = $this->get('form.factory')->create(new BygningDashboardType($this->getDoctrine(), $filterCondition), null, [
        $form = $this->get('form.factory')->create(BygningDashboardType::class, null, [
      'action' => $this->generateUrl('dashboard_'.$filterCondition),
      'method' => 'GET',
    ]);

        if ($request->query->has($form->getName())) {
            // manually bind values from the request
            $form->submit($request->query->get($form->getName()));

            // build the query from the given form object
            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);
        }

        $query = $filterBuilder->getQuery();

        $pagination = $paginator->paginate(
      $query,
      $request->query->get('page', 1) /*page number*/,
      20, // limit per page
      ['defaultSortFieldName' => 'b.updatedAt', 'defaultSortDirection' => 'desc']
    );

        $twigArray = [
      'pagination' => $pagination,
      'form' => $form->createView(),
      'tab' => $filterCondition,
      'aaplusAnsvarlig' => $user->hasGroup('Aa+'),
      'energiRaadgiver' => $user->hasGroup('Rådgiver'),
      'segmenter' => !$user->getSegmenter()->isEmpty(),
      'projektleder' => $user->hasGroup('Projektleder'),
      'projekterende' => $user->hasGroup('Projekterende'),
    ];

        if ('igangvaerende' === $filterCondition || 'indsendt' === $filterCondition) {
            // Summary
            $em = $this->getDoctrine()->getManager();
            $twigArray['summary_current'] = $em->getRepository('AppBundle:Rapport')->getSummaryByUserAndStatus($user, BygningStatusType::TILKNYTTET_RAADGIVER);
            $twigArray['summary_finished'] = $em->getRepository('AppBundle:Rapport')->getSummaryByUserAndStatus($user, BygningStatusType::AFLEVERET_RAADGIVER);
        }

        return $this->render('AppBundle:Dashboard:default.html.twig', $twigArray);
    }

    /**
     * @param User $user
     * @param $filterCondition
     *
     * @return mixed
     */
    private function getDashboardFilterBuilder(User $user, $filterCondition)
    {
        $filterBuilder = $this->get('doctrine.orm.entity_manager')
      ->getRepository('AppBundle:Bygning')
      ->createQueryBuilder('b');
        $filterBuilder->leftJoin('b.rapport', 'r');
        $filterBuilder->leftJoin('b.segment', 's');

        switch ($filterCondition) {
      case 'aaplusAnsvarlig':
        $filterBuilder->andWhere('b.aaplusAnsvarlig = :aaplusAnsvarlig');
        $filterBuilder->setParameter('aaplusAnsvarlig', $user);

        break;
      case 'igangvaerende':
        $filterBuilder->andWhere('b.energiRaadgiver = :energiRaadgiver');
        $filterBuilder->setParameter('energiRaadgiver', $user);
        $filterBuilder->andWhere('b.status = :status');
        $filterBuilder->setParameter('status', BygningStatusType::TILKNYTTET_RAADGIVER);

        break;
      case 'indsendt':
        $filterBuilder->andWhere('b.energiRaadgiver = :energiRaadgiver');
        $filterBuilder->setParameter('energiRaadgiver', $user);
        $filterBuilder->andWhere('b.status >= :status');
        $filterBuilder->setParameter('status', BygningStatusType::AFLEVERET_RAADGIVER);

        break;
      case 'projektleder':
        $filterBuilder->andWhere('b.projektleder = :projektleder');
        $filterBuilder->setParameter('projektleder', $user);

        break;
      case 'projekterende':
        $filterBuilder->andWhere('b.projekterende = :projekterende');
        $filterBuilder->setParameter('projekterende', $user);

        break;
      case 'segmenter':
        $filterBuilder->andWhere('b.segment IN (:segmenter)');
        $filterBuilder->setParameter('segmenter', $user->getSegmenter());

        break;
      default:
        $filterBuilder->andWhere(':user MEMBER OF b.users');
        $filterBuilder->setParameter('user', $user);

        break;
    }

        return $filterBuilder;
    }
}

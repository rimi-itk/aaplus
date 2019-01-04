<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Controller;

use AppBundle\DataExport\ExcelExport;
use AppBundle\Form\Type\BygningUdtraekType;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Yavin\Symfony\Controller\InitControllerInterface;

/**
 * Udtraek controller.
 *
 * @Route("/udtraek")
 * @Security("has_role('ROLE_ADMIN')")
 */
class UdtraekController extends BaseController implements InitControllerInterface
{
    protected $breadcrumbs;

    public function init(Request $request)
    {
        parent::init($request);
        $this->breadcrumbs->addItem('Udtræk', $this->generateUrl('udtraek'));
    }

    /**
     * Get udtraek page.
     *
     * @Route(
     *   ".{_format}",
     *   name="udtraek",
     *   defaults={"_format": "html"},
     *   requirements={
     *     "_format": "html|xlsx|csv",
     *   }, methods={"GET"}
     * )
     * @Template()
     *
     * @param mixed $_format
     */
    public function indexAction(Request $request, $_format)
    {
        // We need more time!
        set_time_limit(0);

        if ($request->query->has('_format')) {
            $value = $request->query->get('_format');
            if ('xlsx' === $value || 'csv' === $value) {
                $_format = $value;
            }
        }

        // initialize a query builder
        $filterBuilder = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:Bygning')
            ->createQueryBuilder('e');

        $form = $this->get('form.factory')->create(BygningUdtraekType::class, null, [
            'action' => $this->generateUrl('udtraek'),
            'method' => 'GET',
        ]);

        if ($request->query->has($form->getName())) {
            // manually bind values from the request
            $form->submit($request->query->get($form->getName()));

            // build the query from the given form object
            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);
        }

        if ($request->query->has('_timestamp')) {
            $spec = $request->query->get('_timestamp');

            try {
                $timestamp = DateTime::createFromFormat(
                    'Y-m-d',
                    $spec['year'].'-'.$spec['month'].'-'.$spec['day']
                );
                if (false !== $timestamp) {
                    $timestamp->setTime(0, 0, 0);

                    return $this->indexAtTimeAction($request, $form, $timestamp, $_format);
                }
            } catch (Exception $ex) {
            }
        }

        $query = $filterBuilder->getQuery();

        $columns = $this->getColumnGroupsInfo($request);
        $types = $this->getTypesInfo($request);
        $type = $this->getType($request);

        if ('html' !== $_format) {
            $result = $query->getResult();

            return $this->export($result, $_format, $columns, $types, $type);
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->get('page', 1) /*page number*/,
            10 // limit per page
        );

        return $this->render('AppBundle:Udtraek:index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'columns' => $columns,
            'types' => $types,
            'type' => $type,
        ]);
    }

    /**
     * Get predefined udtraek page.
     *
     * @Route("/predefineret", name="udtraek_predefined", methods={"GET"})
     * @Template()
     */
    public function predefinedAction()
    {
        return $this->render('AppBundle:Udtraek:predefined.html.twig', []);
    }

    /**
     * Get sum of field pr year excel export.
     *
     * @Route("/field_sum_pr_year/{field}", name="udtraek_field_sum_pr_year", methods={"GET"})
     * @Template()
     *
     * @param mixed $field
     */
    public function fieldSumPrAarAction($field)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $search = [];

        if (empty($field)) {
            throw new HttpException('field not set');
        }

        $results = [];

        // Get all segmenter
        $segments = $em->getRepository('AppBundle:Segment')->findAll();

        // Get all forkortelser
        $forkortelser = [];
        foreach ($segments as $segment) {
            if (!\in_array($segment->getForkortelse(), $forkortelser, true)) {
                $forkortelser[] = $segment->getForkortelse();
            }
        }

        // Get all building types
        $types = $em->getRepository('AppBundle:Bygning')->getBuildingTypes();

        for ($year = 2015; $year <= 2018; ++$year) {
            $search['year'] = $year;

            // Get hele portefølje
            $query = $em->getRepository('AppBundle:Bygning')->getFieldSum($user, $field, $search);
            $result = $query->getSingleScalarResult();
            $results[$year]['Hele portefølje'] = $result;

            $results[$year]['--- Magistrater ---'] = null;

            foreach ($forkortelser as $forkortelse) {
                $search['forkortelse'] = $forkortelse;
                $query = $em->getRepository('AppBundle:Bygning')->getFieldSum($user, $field, $search);

                // Get the results.
                $result = $query->getSingleScalarResult();

                $results[$year][$forkortelse] = $result;
            }

            unset($search['forkortelse']);

            $results[$year]['---- Segmenter ----'] = null;

            // Get segments.
            foreach ($segments as $segment) {
                $search['segment'] = $segment;
                $query = $em->getRepository('AppBundle:Bygning')->getFieldSum($user, $field, $search);

                // Get the results.
                $result = $query->getSingleScalarResult();
                $results[$year][$segment->getForkortelse().' '.$segment->getNavn()] = $result;
            }

            unset($search['segment']);

            $results[$year]['---- Bygningstyper ----'] = null;

            // Get segments.
            foreach ($types as $type) {
                $type = $type['type'];
                if (null === $type) {
                    $type = 'Ikke valgt';
                }

                $search['type'] = $type;
                $query = $em->getRepository('AppBundle:Bygning')->getFieldSum($user, $field, $search);

                // Get the results.
                $result = $query->getSingleScalarResult();
                $results[$year][$type] = $result;
            }
        }

        return ExcelExport::generateTwoDimensionalExcelResponse(
            $results,
            'udtraek--'.$field.'-pr-aar--'.date('d-m-Y_Hi').'.xlsx'
        );
    }

    /**
     * Get avg of the diff between field and baseline pr year excel export.
     *
     * @Route("/field_avg_diff_pr_year/{field}/{baseline}", name="udtraek_field_avg_diff_pr_year", methods={"GET"})
     * @Template()
     *
     * @param mixed $field
     * @param mixed $baseline
     */
    public function fieldAvgDiffPrAarAction($field, $baseline)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $search = [];

        if (empty($field)) {
            throw new HttpException('field not set');
        }

        $results = [];

        // Get all segmenter
        $segments = $em->getRepository('AppBundle:Segment')->findAll();

        // Get all forkortelser
        $forkortelser = [];
        foreach ($segments as $segment) {
            if (!\in_array($segment->getForkortelse(), $forkortelser, true)) {
                $forkortelser[] = $segment->getForkortelse();
            }
        }

        // Get all building types
        $types = $em->getRepository('AppBundle:Bygning')->getBuildingTypes();

        for ($year = 2015; $year <= 2018; ++$year) {
            $search['year'] = $year;

            // Get hele portefølje
            $query = $em->getRepository('AppBundle:Bygning')->getFieldAvgDiff($user, $field, $baseline, $search);
            $result = $query->getSingleScalarResult();
            $results[$year]['Hele portefølje'] = $result;

            $results[$year]['--- Magistrater ---'] = null;

            foreach ($forkortelser as $forkortelse) {
                $search['forkortelse'] = $forkortelse;
                $query = $em->getRepository('AppBundle:Bygning')->getFieldAvgDiff($user, $field, $baseline, $search);

                // Get the results.
                $result = $query->getSingleScalarResult();

                $results[$year][$forkortelse] = $result;
            }

            unset($search['forkortelse']);

            $results[$year]['---- Segmenter ----'] = null;

            // Get segments.
            foreach ($segments as $segment) {
                $search['segment'] = $segment;
                $query = $em->getRepository('AppBundle:Bygning')->getFieldAvgDiff($user, $field, $baseline, $search);

                // Get the results.
                $result = $query->getSingleScalarResult();
                $results[$year][$segment->getForkortelse().' '.$segment->getNavn()] = $result;
            }

            unset($search['segment']);

            $results[$year]['---- Bygningstyper ----'] = null;

            // Get segments.
            foreach ($types as $type) {
                $type = $type['type'];
                $search['type'] = $type;
                $query = $em->getRepository('AppBundle:Bygning')->getFieldAvgDiff($user, $field, $baseline, $search);

                // Get the results.
                $result = $query->getSingleScalarResult();
                $results[$year][$type] = $result;
            }
        }

        return ExcelExport::generateTwoDimensionalExcelResponse(
            $results,
            'udtraek--'.$field.'-'.$baseline.'-diff-pr-aar--'.date('d-m-Y_Hi').'.xlsx'
        );
    }

    /**
     * Get Bygning data from at specific point in time (in the past).
     *
     * @param mixed $_format
     */
    private function indexAtTimeAction(Request $request, Form $form, DateTime $timestamp, $_format)
    {
        $entities = $this->get('doctrine.orm.entity_manager')->getRepository('AppBundle:Bygning')
            ->setContainer($this->container)
            ->findAtTime($timestamp, $form);

        $columns = $this->getColumnGroupsInfo($request);
        $types = $this->getTypesInfo($request);
        $type = $this->getType($request);

        if ('html' !== $_format) {
            return $this->export($entities, $_format, $columns, $types, $type);
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities,
            $request->query->get('page', 1) /*page number*/,
            10 // limit per page
        );

        return $this->render('AppBundle:Udtraek:index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'columns' => $columns,
            'types' => $types,
            'type' => $type,
            'timestamp' => $timestamp,
        ]);
    }

    private function getColumnGroupsInfo(Request $request)
    {
        $groups = [
            'name' => '_columns',
            'groups' => [],
        ];
        $values = $request->query->get($groups['name']);
        $names = [
            UdtraekColumnGroups::ALT,
            UdtraekColumnGroups::BYGNINGSINFORMATION,
            UdtraekColumnGroups::BASELINEINFORMATION,
            UdtraekColumnGroups::AA_SCREENINGSINFORMATION,
            UdtraekColumnGroups::BESPARELSESINFORMATION,
            UdtraekColumnGroups::OEKONOMI,
        ];
        foreach ($names as $name) {
            $groups['groups'][$name] = isset($values[$name]) && !empty($values[$name]);
        }

        return $groups;
    }

    private function getTypesInfo(Request $request)
    {
        $types = [
            'name' => '_entity',
            'types' => [],
        ];
        $value = $request->query->get($types['name'], UdtraekType::BYGNING);
        if (!$value) {
            $value = UdtraekType::BYGNING;
        }
        $names = [
            UdtraekType::BYGNING,
            UdtraekType::TILTAG,
        ];
        foreach ($names as $name) {
            $types['types'][$name] = ($name === $value);
        }

        return $types;
    }

    private function getType(Request $request)
    {
        $types = $this->getTypesInfo($request);
        foreach ($types['types'] as $type => $value) {
            if ($value) {
                return $type;
            }
        }

        return UdtraekType::BYGNING;
    }

    private function export(array $result, $format, $columns, $types, $type)
    {
        $filename = 'bygninger--'.date('d-m-Y_Hi').'.'.$format;

        $streamer = $this->container->get('aaplus.exporter.bygning_stream');
        $streamer->setConfig([
            'columns' => $columns,
            'types' => $types,
            'type' => $type,
        ]);

        switch ($format) {
            case 'csv':
                $contentType = 'text/csv';

                break;
            case 'xlsx':
                $contentType = 'application/vnd.ms-excel';

                break;
        }

        $response = new StreamedResponse();
        $response->headers->add([
            'Content-type' => $contentType,
            'Content-disposition' => 'attachment; filename="'.$filename.'"',
            'Cache-control' => 'max-age=0',
        ]);

        $response->setCallback(function () use ($result, $streamer, $format) {
            $streamer->start('php://output', $format);
            $streamer->header();
            foreach ($result as $item) {
                $streamer->item($item);
            }
            $streamer->end();
        });

        return $response;
    }

    private function getColumnGroups(Request $request)
    {
        $info = $this->getColumnGroupsInfo($request);
        $groups = [];
        foreach ($info['groups'] as $name => $value) {
            if ($value) {
                $groups[$name] = $name;
            }
        }

        return $groups;
    }
}

abstract class UdtraekColumnGroups
{
    const ALT = 'alt';
    const BYGNINGSINFORMATION = 'bygningsinformation';
    const BASELINEINFORMATION = 'baselineinformation';
    const AA_SCREENINGSINFORMATION = 'aa_screeningsinformation';
    const BESPARELSESINFORMATION = 'besparelsesinformation';
    const OEKONOMI = 'oekonomi';
}

abstract class UdtraekType
{
    const BYGNING = 'bygning';
    const TILTAG = 'tiltag';
}

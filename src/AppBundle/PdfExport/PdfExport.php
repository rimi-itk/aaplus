<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\PdfExport;

use AppBundle\Entity\Rapport;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PdfExport
{
    private $container;
    private $templating;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->templating = $this->container->get('templating');
    }

    public function export2(Rapport $rapport, array $options = [])
    {
        $html = $this->renderView('AppBundle:Rapport:showPdf2.html.twig', [
            'rapport' => $rapport,
        ]);

        $cover = $this->renderView('AppBundle:Rapport:showPdf2Cover.html.twig', [
            'rapport' => $rapport,
        ]);

        return $this->container->get('knp_snappy.pdf')->getOutputFromHtml($html, array_merge(
            [
                'lowquality' => false,
                'encoding' => 'utf-8',
                'images' => true,
                'cover' => $cover,
                'header-html' => $this->container->get('request')->getSchemeAndHttpHost().'/html/pdf2Header.html',
                'footer-left' => $rapport->getBygning(),
                'footer-right' => 'Side [page] af [toPage]',
            ],
            $options
        ));
    }

    public function export5(Rapport $rapport, array $options = [])
    {
        $html = $this->renderView('AppBundle:Rapport:showPdf5.html.twig', [
            'rapport' => $rapport,
        ]);

        $cover = $this->renderView('AppBundle:Rapport:showPdf5Cover.html.twig', [
            'rapport' => $rapport,
        ]);

        return $this->container->get('knp_snappy.pdf')->getOutputFromHtml($html, array_merge(
            [
                'orientation' => 'Landscape',
                'lowquality' => false,
                'encoding' => 'utf-8',
                'images' => true,
                'cover' => $cover,
                'header-html' => $this->container->get('request')->getSchemeAndHttpHost().'/html/pdf5Header.html',
                'footer-left' => $rapport->getBygning(),
                'footer-right' => 'Side [page] af [toPage]',
            ],
            $options
        ));
    }

    private function renderView($view, array $parameters = [])
    {
        return $this->templating->render($view, $parameters);
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Command;

use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PostMigrate20160711133430Command extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
      ->setName('aaplus:post-migrate:20160711133430')
      ->setDescription('Run code after doctrine migration 20160711133430');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->Version20160711133430();
    }

    /**
     * Recalculate Rapport to update properties depending of the values updated.
     */
    private function Version20160711133430()
    {
        echo __METHOD__, PHP_EOL;

        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getEntityManager('default');
        $repository = $em->getRepository('AppBundle:Rapport');
        $rapporter = $repository->findAll();

        $reflectionClass = null;

        foreach ($rapporter as $rapport) {
            if (null === $reflectionClass) {
                $reflectionClass = new ReflectionClass(\get_class($rapport));
            }

            $oldBesparelseVarmeGUF = (float) ($rapport->getBesparelseVarmeGUF());
            $oldBesparelseVarmeGAF = (float) ($rapport->getBesparelseVarmeGAF());
            $oldFravalgtBesparelseVarmeGUF = (float) ($rapport->getFravalgtBesparelseVarmeGUF());
            $oldFravalgtBesparelseVarmeGAF = (float) ($rapport->getFravalgtBesparelseVarmeGAF());

            $rapport->calculate();

            $newBesparelseVarmeGUF = (float) ($rapport->getBesparelseVarmeGUF());
            $newBesparelseVarmeGAF = (float) ($rapport->getBesparelseVarmeGAF());
            $newFravalgtBesparelseVarmeGUF = (float) ($rapport->getFravalgtBesparelseVarmeGUF());
            $newFravalgtBesparelseVarmeGAF = (float) ($rapport->getFravalgtBesparelseVarmeGAF());

            $changedValues = [];
            if ($newBesparelseVarmeGUF !== $oldBesparelseVarmeGUF) {
                $changedValues['besparelseVarmeGUF'] = [
          'old' => $oldBesparelseVarmeGUF,
          'new' => $newBesparelseVarmeGUF,
        ];
            }
            if ($newBesparelseVarmeGAF !== $oldBesparelseVarmeGAF) {
                $changedValues['besparelseVarmeGAF'] = [
          'old' => $oldBesparelseVarmeGAF,
          'new' => $newBesparelseVarmeGAF,
        ];
            }
            if ($newFravalgtBesparelseVarmeGUF !== $oldFravalgtBesparelseVarmeGUF) {
                $changedValues['fravalgtBesparelseVarmeGUF'] = [
          'old' => $oldFravalgtBesparelseVarmeGUF,
          'new' => $newFravalgtBesparelseVarmeGUF,
        ];
            }
            if ($newFravalgtBesparelseVarmeGAF !== $oldFravalgtBesparelseVarmeGAF) {
                $changedValues['fravalgtBesparelseVarmeGAF'] = [
          'old' => $oldFravalgtBesparelseVarmeGAF,
          'new' => $newFravalgtBesparelseVarmeGAF,
        ];
            }

            if ($changedValues) {
                foreach ($changedValues as $name => $values) {
                    $property = $reflectionClass->getProperty($name);
                    $property->setAccessible(true);
                    $property->setValue($rapport, $values['new']);
                }
                $em->persist($rapport);

                echo sprintf('rapport %d: %s', $rapport->getId(), var_export($changedValues, true)), PHP_EOL;
            }
        }
    }
}

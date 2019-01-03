<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\DataFixtures\ORM;

use Ddeboer\DataImport\Reader\CsvReader;
use Ddeboer\DataImport\Workflow;
use Ddeboer\DataImport\Writer\ConsoleProgressWriter;
use Ddeboer\DataImport\Writer\WriterInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadUserData.
 */
abstract class LoadData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    protected $order = 1;
    protected $flush = true;

    /** @var ConsoleOutput $output */
    private $output;

    public function __construct()
    {
        $this->output = new ConsoleOutput();
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $basepath = $this->container->get('kernel')->locateResource('@AppBundle/DataFixtures/Data/');
        $filename = $this->getFilename();

        if (file_exists($basepath.$filename)) {
            // Create and configure the reader
            $file = new \SplFileObject($basepath.$filename);
            $csvReader = new CsvReader($file, ';');
            $progressWriter = new ConsoleProgressWriter($this->output, $csvReader, 'normal', 100);

            // Tell the reader that the first row in the CSV file contains column headers
            $csvReader->setHeaderRowNumber(0);

            // Create the workflow from the reader
            $workflow = new Workflow($csvReader);

            $workflow->addWriter($this->createWriter($manager));
            $workflow->addWriter($progressWriter);

            // Process the workflow

            $workflow->process();
            $this->done($manager);

            $this->writeInfo($filename.' imported succesfully');
        } else {
            $this->writeError($filename.' not found. Did you forget to add it to AppBundle/DataFixtures/Data?');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set a reference to an entity for later retrieval.
     *
     * @param string $type
     * @param string $id
     * @param object $entity
     */
    protected function setEntityReference($type, $id, $entity)
    {
        $key = $type.':'.$id;
        $this->setReference($key, $entity);
    }

    final protected function writeInfo($message)
    {
        if (\func_num_args() > 1) {
            $message = \call_user_func_array('sprintf', \func_get_args());
        }
        // $this->output->writeln('');
        $this->output->writeln('  <comment>></comment> <info>'.$message.'</info>');
    }

    final protected function writeError($message)
    {
        if (\func_num_args() > 1) {
            $message = \call_user_func_array('sprintf', \func_get_args());
        }
        $this->output->writeln('');
        $this->output->writeln('  <comment>></comment> <error>'.$message.'</error>');
    }

    protected function getFilename()
    {
        $ancestor = get_parent_class($this);
        while ($ancestor && $ancestor instanceof self) {
            $ancestor = get_parent_class($ancestor);
        }

        if (!$ancestor) {
            $ancestor = \get_class($this);
        }

        $ancestorNamespace = substr($ancestor, 0, strrpos($ancestor, '\\'));
        if (0 === strpos(\get_class($this), $ancestorNamespace)) {
            $path = substr(\get_class($this), \strlen($ancestorNamespace) + 1);
        }

        return preg_replace(['@\\\\Load@', '@^Load@', '@\\\\@'], ['\\', '', '/'], $path).'.csv';
    }

    /**
     * @param ObjectManager $manager
     *
     * @return WriterInterface
     */
    abstract protected function createWriter(ObjectManager $manager);

    protected function done(ObjectManager $manager)
    {
        if ($this->flush) {
            $manager->flush();
        }
    }
}

<?php

/*
 * This file is part of aaplusplus.
 *
 * (c) 2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace AppBundle\Tests\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class EntityTestCase extends KernelTestCase
{
    /**
     * Allowed deviance when comparing numbers.
     *
     * @var float
     */
    protected $allowedDeviance = 0.00001;

    protected static $classNames = [
        'configuration' => '\AppBundle\Entity\Configuration',
        'bygning' => '\AppBundle\Entity\Bygning',
        'forsyningsvaerkVarme' => '\AppBundle\Entity\Forsyningsvaerk',
        'forsyningsvaerkEl' => '\AppBundle\Entity\Forsyningsvaerk',
        'forsyningsvaerkVand' => '\AppBundle\Entity\Forsyningsvaerk',
        'energiforsyninger' => '\AppBundle\Entity\Energiforsyning',
        'internProduktioner' => '\AppBundle\Entity\Energiforsyning\InternProduktion',
        'forsyningVarme' => '\AppBundle\Entity\Energiforsyning',
        'forsyningEl' => '\AppBundle\Entity\Energiforsyning',
    ];

    public function loadProperties(array $properties)
    {
        return $properties;
    }

    /**
     * Set properties on an entity.
     *
     * @param object $entity
     *                           The entity
     * @param array  $properties
     *                           The properties
     *
     * @return object
     *                The entity
     */
    protected function setProperties($entity, array $properties)
    {
        foreach ($properties as $name => $value) {
            if (null !== $value) {
                if ('id' === $name) {
                    $reflectionClass = new \ReflectionClass($entity);
                    $reflectionProperty = $reflectionClass->getProperty('id');
                    $reflectionProperty->setAccessible(true);
                    $reflectionProperty->setValue($entity, $value);
                } else {
                    $propertyName = $this->getPropertyName($name);
                    $entity->{'set'.$propertyName}($value);
                }
            }
        }

        return $entity;
    }

    /**
     * Assert that properties on an entity equals expected values.
     *
     * @param array  $properties
     *                           The properties
     * @param object $entity
     *                           The entity
     */
    protected function assertProperties(array $properties, $entity)
    {
        if ($properties) {
            foreach ($properties as $name => $value) {
                $propertyName = $this->getPropertyName($name);
                $this->assertAlmostEquals($value, $entity->{'get'.$propertyName}(), __METHOD__.' '.$propertyName);
            }
        }
    }

    protected function loadEntity($entity, array $data)
    {
        if (!isset($data['_input'])) {
            $entity = $this->setProperties($entity, $data);
        } else {
            $entity = $this->setProperties($entity, $data['_input']);

            foreach ($data as $property => $values) {
                if (!preg_match('/^_/', $property)) {
                    $className = $this->getEntityClassName($property);
                    if ($className && $values) {
                        $value = self::isAssoc($values) ? $this->loadEntity(new $className(), $values) : $this->loadEntities(new $className(), $values);
                        $entity->{'set'.$property}($value);
                    }
                }
            }
        }

        return $entity;
    }

    protected function loadEntities($entity, array $data)
    {
        $entities = [];
        foreach ($data as $values) {
            $entities[] = $this->loadEntity(clone $entity, $values);
        }

        return new \Doctrine\Common\Collections\ArrayCollection($entities);
    }

    protected function getEntityClassName($property)
    {
        if (isset(self::$classNames[$property])) {
            return self::$classNames[$property];
        }

        throw new \Exception('Unknown class name for property '.$property);
    }

    /**
     * Get test class name for an entity.
     *
     * @param object $entity
     *                       The entity
     *
     * @return string
     *                The entity test class name
     */
    protected function getTestClassName($entity)
    {
        $className = \get_class($entity);
        $testClassName = preg_replace('/AppBundle\\\\/', 'AppBundle\\Tests\\', $className).'Test';

        return $testClassName;
    }

    /**
     * Load test fixtures from file.
     *
     * @param string $type
     *                     The type, i.e. an entity class name.
     *
     * @return array
     *               array(properties, expected)
     */
    protected function loadTestFixtures($type = null)
    {
        $fixtures = [];

        $testFixturesPath = $this->getAppBundlePath().'DataFixtures/Data/fixtures';
        $filepaths = glob($testFixturesPath.'/*/'.($type ? $type : '*'));

        foreach ($filepaths as $filepath) {
            if (($content = @file_get_contents($filepath))) {
                $type = basename($filepath);
                $name = basename(\dirname($filepath));

                if (!isset($fixtures[$name])) {
                    $fixtures[$name] = [];
                }

                try {
                    $fixtures[$name][$type] = json_decode($content, true);
                } catch (\Exception $ex) {
                }
            }
        }

        return $fixtures;
    }

    protected function assertAlmostEquals($expected, $actual, $message)
    {
        $delta = abs(min($expected, $actual) * $this->allowedDeviance);
        $this->assertEquals($expected, $actual, $message, $delta);
    }

    // http://stackoverflow.com/a/173479
    private static function isAssoc($arr)
    {
        return array_keys($arr) !== range(0, \count($arr) - 1);
    }

    /**
     * Convert snake_case to PascalCase.
     *
     * @param string $name
     *                     The name
     *
     * @return string
     *                The key converted to PascalCase
     */
    private function getPropertyName($name)
    {
        return preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '').strtoupper($match[2]);
        }, $name);
    }

    private function getAppBundlePath()
    {
        if (preg_match('@^(.+/AppBundle/)@', __FILE__, $matches)) {
            return $matches[0];
        }

        return null;
    }
}

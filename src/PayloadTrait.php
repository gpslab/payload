<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload;

use GpsLab\Component\Payload\Exception\PropertyException;

trait PayloadTrait
{
    /**
     * @var array
     */
    private $payload = [];

    /**
     * @var string[]
     */
    private $properties = [];

    /**
     * @var string[]
     */
    private $methods = [];

    /**
     * @return array
     */
    final public function payload()
    {
        return $this->payload;
    }

    /**
     * @param array $payload
     *
     * @throws PropertyException
     */
    final protected function setPayload(array $payload)
    {
        $this->analyze();

        foreach ($payload as $name => $value) {
            $this->setProperty($name, $value);
        }

        $this->payload = $payload;
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    private function setProperty($name, $value)
    {
        if (in_array($name, $this->properties)) {
            $this->$name = $value;
        } elseif (($method = 'set'.ucfirst($name)) && in_array($method, $this->methods)) {
            $this->{$method}($value);
        } else {
            throw PropertyException::undefinedProperty($name, $this);
        }
    }

    private function analyze()
    {
        if (!$this->properties && !$this->methods) {
            $ref = new \ReflectionClass($this);
            $this->properties = $this->getProperties($ref);
            $this->methods = $this->getMethods($ref);
        }
    }

    /**
     * @param \ReflectionClass $ref
     *
     * @return string[]
     */
    private function getProperties(\ReflectionClass $ref)
    {
        $names = [];
        $properties = $ref->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        foreach ($properties as $property) {
            $names[] = $property->name;
        }

        return $names;
    }

    /**
     * @param \ReflectionClass $ref
     *
     * @return string[]
     */
    private function getMethods(\ReflectionClass $ref)
    {
        $names = [];
        $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED);
        foreach ($methods as $method) {
            $names[] = $method->name;
        }

        return $names;
    }
}

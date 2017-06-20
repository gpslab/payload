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
     * @var array
     */
    private $properties = [];

    /**
     * @var array
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
     * @param array $required
     *
     * @throws PropertyException
     */
    final protected function setPayload(array $payload, array $required = [])
    {
        if (!empty($required) && ($lost = array_diff($required, array_keys($payload)))) {
            throw PropertyException::noRequiredProperties($lost, $this);
        }

        $this->analyze();

        foreach ($payload as $name => $value) {
            if (in_array($name, $this->properties)) {
                $this->$name = $value;
                continue;
            } elseif (($method = 'set'.ucfirst($name)) && in_array($method, $this->methods)) {
                $this->{$method}($value);
                continue;
            }

            throw PropertyException::undefinedProperty($name, $this);
        }

        $this->payload = $payload;
    }

    /**
     * @return array
     */
    private function analyze()
    {
        if (!$this->properties && !$this->methods) {
            $ref = new \ReflectionClass($this);

            $properties = $ref->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
            foreach ($properties as $property) {
                $this->properties[] = $property->name;
            }

            $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC | \ReflectionMethod::IS_PROTECTED);
            foreach ($methods as $method) {
                $this->methods[] = $method->name;
            }
        }
    }
}

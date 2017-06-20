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
        $properties = $this->getProperties();

        foreach ($payload as $name => $value) {
            if (!in_array($name, $properties)) {
                throw PropertyException::undefinedProperty($name, $this);
            }

            $this->$name = $value;
        }

        $this->payload = $payload;
    }

    /**
     * @return array
     */
    private function getProperties()
    {
        if (!$this->properties) {
            $ref = new \ReflectionClass($this);
            $properties = $ref->getProperties(\ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PUBLIC);
            foreach ($properties as $property) {
                $this->properties[] = $property->getName();
            }
        }

        return $this->properties;
    }
}

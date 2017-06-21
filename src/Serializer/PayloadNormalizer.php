<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Serializer;

use GpsLab\Component\Payload\Payload;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PayloadNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @param mixed       $data
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Payload;
    }

    /**
     * @param Payload     $object
     * @param string|null $format
     * @param array       $context
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return $object->payload();
    }

    /**
     * @param array       $data
     * @param string      $class
     * @param string|null $format
     * @param array       $context
     *
     * @return Payload
     */
    public function denormalize($data, $class, $format = null, array $context = [])
    {
        return new $class($data);
    }

    /**
     * @param mixed       $data
     * @param string      $type
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return is_array($data) && is_subclass_of($type, Payload::class);
    }
}

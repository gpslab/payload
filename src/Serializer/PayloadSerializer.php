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
use GpsLab\Component\Payload\Serializer\MessageResolver\MessageResolver;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PayloadSerializer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @var MessageResolver
     */
    private $resolver;

    /**
     * @param MessageResolver $resolver
     */
    public function __construct(MessageResolver $resolver)
    {
        $this->resolver = $resolver;
    }

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
        return [
            'type' => $this->resolver->typeOfMessage($object),
            'payload' => $object->payload(),
        ];
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
        return new $class($data['payload']);
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
        return
            is_array($data) &&
            isset($data['type'], $data['payload']) &&
            is_array($data['payload']) &&
            $this->resolver->isTypeOfMessage($data['type'], $type)
        ;
    }
}

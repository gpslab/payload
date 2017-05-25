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
use Symfony\Component\Serializer\Exception\UnsupportedException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Not good, because we are losing the \DateTime and other ValueObjects.
 * There will be problems if the names of the serializable classes change.
 */
class PayloadSerializer implements NormalizerInterface, DenormalizerInterface
{
    const PATTERN = '%s|%s';
    const REGEXP = '/^
            (?<class>[a-zA-Z_][a-zA-Z0-9_]*)| # class name
            (?<payload>.+)                    # payload
        $/x';

    /**
     * @var int
     */
    private $json_options = 0;

    /**
     * @param int $json_options
     */
    public function __construct($json_options = 0)
    {
        $this->json_options = $json_options;
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
     * @return string
     */
    public function normalize($object, $format = null, array $context = [])
    {
        return sprintf(self::PATTERN, get_class($object), json_encode($object->payload(), $this->json_options));
    }

    /**
     * @param string      $data
     * @param string      $type
     * @param string|null $format
     * @param array       $context
     *
     * @return Payload
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (!preg_match(self::REGEXP, $data, $match)) {
            throw new UnsupportedException();
        }

        return new $match['class'](json_decode($match['payload'], true));
    }

    /**
     * @param string      $data
     * @param string      $type
     * @param string|null $format
     *
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Payload::class && preg_match(self::REGEXP, $data);
    }
}

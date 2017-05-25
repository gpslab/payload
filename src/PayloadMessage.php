<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload;

abstract class PayloadMessage implements Payload
{
    use PayloadTrait;

    /**
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->setPayload($payload);
    }
}

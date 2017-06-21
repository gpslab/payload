<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Serializer\MessageTypeResolver;

use GpsLab\Component\Payload\Payload;

interface MessageTypeResolver
{
    /**
     * @param Payload $message
     *
     * @return string
     */
    public function typeOfMessage(Payload $message);

    /**
     * @param string $type
     * @param string $message_class
     *
     * @return bool
     */
    public function isTypeOfMessage($type, $message_class);
}

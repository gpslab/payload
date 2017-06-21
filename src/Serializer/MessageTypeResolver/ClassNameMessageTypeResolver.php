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

/**
 * This resolver use last part of class name.
 *
 * Example:
 *  \Acme\Demo\SomeMessage -> SomeMessage
 *  \Acme_Demo_SomeMessage -> SomeMessage
 *
 * Be careful with the use of this resolver and do not named message classes equally in different namespace.
 */
class ClassNameMessageTypeResolver implements MessageTypeResolver
{
    /**
     * @param Payload $message
     *
     * @return string
     */
    public function typeOfMessage(Payload $message)
    {
        return $this->typeOfMessageClass(get_class($message));
    }

    /**
     * @param string $type
     * @param string $message_class
     *
     * @return bool
     */
    public function isTypeOfMessage($type, $message_class)
    {
        return $this->typeOfMessageClass($message_class) === $type;
    }

    /**
     * @param string $message_class
     *
     * @return string
     */
    private function typeOfMessageClass($message_class)
    {
        $parts = explode('\\', str_replace('_', '\\', $message_class));

        return end($parts);
    }
}

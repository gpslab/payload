<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Tests\Fixture;

use GpsLab\Component\Payload\PayloadMessage;

class SomeMessage extends PayloadMessage
{
    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @param array $payload
     */
    public function __construct(array $payload)
    {
        $this->setPayload($payload, true);
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return \DateTime
     */
    public function date()
    {
        return $this->date;
    }
}

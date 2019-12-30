<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Tests;

use GpsLab\Component\Payload\Tests\Fixture\SomeMessage;
use PHPUnit\Framework\TestCase;

class PayloadMessageTest extends TestCase
{
    public function testSetPayload()
    {
        $payload = [
            'id' => 123,
            'name' => 'foo',
            'date' => new \DateTime(),
        ];

        $message = new SomeMessage($payload);

        $this->assertEquals($payload['id'], $message->id());
        $this->assertEquals($payload['name'], $message->name());
        $this->assertEquals($payload['date'], $message->date());
    }

    /**
     * @expectedException \GpsLab\Component\Payload\Exception\PropertyException
     */
    public function testSetPayloadUndefinedProperty()
    {
        $payload = [
            'foo' => 123,
        ];

        new SomeMessage($payload);
    }
}

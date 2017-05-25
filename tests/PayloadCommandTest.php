<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Tests;

use GpsLab\Component\Payload\Payload;
use GpsLab\Component\Payload\PayloadMessage;
use GpsLab\Component\Payload\PayloadCommand;
use GpsLab\Component\Payload\Tests\Fixture\UpdateContactCommand;
use GpsLab\Component\Command\Command;

class PayloadCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testSetPayload()
    {
        $payload = [
            'contact_id' => 123,
            'name' => 'foo',
        ];
        $query = new UpdateContactCommand($payload);

        $this->assertEquals($payload['contact_id'], $query->contactId());
        $this->assertEquals($payload['name'], $query->name());
        $this->assertEquals($payload, $query->payload());
        $this->assertInstanceOf(PayloadCommand::class, $query);
        $this->assertInstanceOf(Command::class, $query);
        $this->assertInstanceOf(PayloadMessage::class, $query);
        $this->assertInstanceOf(Payload::class, $query);
    }

    /**
     * @expectedException \GpsLab\Component\Payload\Exception\UndefinedPropertyException
     */
    public function testTrySetHideOption()
    {
        new UpdateContactCommand(['hide_option' => 'foo']);
    }

    /**
     * @expectedException \GpsLab\Component\Payload\Exception\UndefinedPropertyException
     */
    public function testTrySetUndefinedOption()
    {
        new UpdateContactCommand(['undefined' => 'foo']);
    }
}

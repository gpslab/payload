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
use GpsLab\Component\Payload\PayloadDomainEvent;
use GpsLab\Component\Payload\Tests\Fixture\RenamedContactEvent;
use GpsLab\Domain\Event\EventInterface;

class PayloadDomainEventTest extends \PHPUnit_Framework_TestCase
{
    public function testSetPayload()
    {
        $payload = [
            'contact_id' => 123,
            'old_name' => 'foo',
            'new_name' => 'bar',
        ];
        $query = new RenamedContactEvent($payload);

        $this->assertEquals($payload['contact_id'], $query->contact_id);
        $this->assertEquals($payload['old_name'], $query->old_name);
        $this->assertEquals($payload['new_name'], $query->new_name);
        $this->assertEquals($payload, $query->payload());
        $this->assertInstanceOf(PayloadDomainEvent::class, $query);
        $this->assertInstanceOf(EventInterface::class, $query);
        $this->assertInstanceOf(PayloadMessage::class, $query);
        $this->assertInstanceOf(Payload::class, $query);
    }

    /**
     * @expectedException \GpsLab\Component\Payload\Exception\UndefinedPropertyException
     */
    public function testTrySetHideOption()
    {
        new RenamedContactEvent(['hide_option' => 'foo']);
    }

    /**
     * @expectedException \GpsLab\Component\Payload\Exception\UndefinedPropertyException
     */
    public function testTrySetUndefinedOption()
    {
        new RenamedContactEvent(['undefined' => 'foo']);
    }
}

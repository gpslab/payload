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
use GpsLab\Component\Payload\PayloadQuery;
use GpsLab\Component\Payload\Tests\Fixture\ContactByIdentityQuery;
use GpsLab\Component\Query\Query;
use PHPUnit\Framework\TestCase;

class PayloadQueryTest extends TestCase
{
    public function testSetPayload()
    {
        $payload = [
            'id' => 123,
        ];
        $query = new ContactByIdentityQuery($payload);

        $this->assertEquals($payload['id'], $query->id());
        $this->assertEquals($payload, $query->payload());
        $this->assertInstanceOf(PayloadQuery::class, $query);
        $this->assertInstanceOf(Query::class, $query);
        $this->assertInstanceOf(PayloadMessage::class, $query);
        $this->assertInstanceOf(Payload::class, $query);
    }

    /**
     * @expectedException \GpsLab\Component\Payload\Exception\PropertyException
     */
    public function testTrySetHideOption()
    {
        new ContactByIdentityQuery(['hide_option' => 'foo']);
    }

    /**
     * @expectedException \GpsLab\Component\Payload\Exception\PropertyException
     */
    public function testTrySetUndefinedOption()
    {
        new ContactByIdentityQuery(['undefined' => 'foo']);
    }
}

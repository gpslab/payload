<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Tests\Serializer;

use GpsLab\Component\Payload\Payload;
use GpsLab\Component\Payload\Serializer\PayloadNormalizer;
use GpsLab\Component\Payload\Tests\Fixture\ContactByIdentityQuery;
use PHPUnit\Framework\TestCase;

class PayloadNormalizerTest extends TestCase
{
    /**
     * @var PayloadNormalizer
     */
    private $serializer;

    protected function setUp()
    {
        $this->serializer = new PayloadNormalizer();
    }

    public function testSupportsNormalization()
    {
        $this->assertTrue($this->serializer->supportsNormalization(new ContactByIdentityQuery([])));
        $this->assertFalse($this->serializer->supportsNormalization(new \stdClass()));
    }

    public function testNormalize()
    {
        $payload = ['foo' => 123];

        /* @var $object \PHPUnit_Framework_MockObject_MockObject|Payload */
        $object = $this->getMock(Payload::class);
        $object
            ->expects($this->once())
            ->method('payload')
            ->will($this->returnValue($payload))
        ;

        $data = $this->serializer->normalize($object);

        $this->assertEquals($payload, $data);
    }

    public function testSupportsDenormalization()
    {
        $payload = [
            'id' => 123,
        ];

        $this->assertFalse($this->serializer->supportsDenormalization('', \stdClass::class));
        $this->assertFalse($this->serializer->supportsDenormalization('foo', \stdClass::class));
        $this->assertFalse($this->serializer->supportsDenormalization([], \stdClass::class));
        $this->assertFalse($this->serializer->supportsDenormalization($payload, \stdClass::class));
        $this->assertTrue($this->serializer->supportsDenormalization($payload, ContactByIdentityQuery::class));
    }

    public function testSupportsDenormalizationNoClass()
    {
        $this->assertFalse($this->serializer->supportsDenormalization(['id' => 123], 'foo'));
    }

    public function testDenormalize()
    {
        $payload = [
            'id' => 123,
        ];

        /* @var $object ContactByIdentityQuery */
        $object = $this->serializer->denormalize($payload, ContactByIdentityQuery::class);

        $this->assertInstanceOf(ContactByIdentityQuery::class, $object);
        $this->assertEquals($payload['id'], $object->id());
    }
}

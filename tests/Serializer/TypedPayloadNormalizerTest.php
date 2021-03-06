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
use GpsLab\Component\Payload\Serializer\MessageTypeResolver\MessageTypeResolver;
use GpsLab\Component\Payload\Serializer\TypedPayloadNormalizer;
use GpsLab\Component\Payload\Tests\Fixture\ContactByIdentityQuery;
use PHPUnit\Framework\TestCase;

class TypedPayloadNormalizerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|MessageTypeResolver
     */
    private $resolver;

    /**
     * @var TypedPayloadNormalizer
     */
    private $serializer;

    protected function setUp()
    {
        $this->resolver = $this->getMock(MessageTypeResolver::class);
        $this->serializer = new TypedPayloadNormalizer($this->resolver);
    }

    public function testSupportsNormalization()
    {
        $this->assertTrue($this->serializer->supportsNormalization(new ContactByIdentityQuery([])));
        $this->assertFalse($this->serializer->supportsNormalization(new \stdClass()));
    }

    public function testNormalize()
    {
        $type = 'foo';
        $payload = ['foo' => 123];

        /* @var $object \PHPUnit_Framework_MockObject_MockObject|Payload */
        $object = $this->getMock(Payload::class);
        $object
            ->expects($this->once())
            ->method('payload')
            ->will($this->returnValue($payload))
        ;

        $this->resolver
            ->expects($this->once())
            ->method('typeOfMessage')
            ->with($object)
            ->will($this->returnValue($type))
        ;

        $data = $this->serializer->normalize($object);

        $this->assertEquals(['type' => $type, 'payload' => $payload], $data);
    }

    /**
     * @return array
     */
    public function invalidData()
    {
        return [
            [''],
            [['foo' => 123]],
            [['type' => 'foo', 'payload' => '']],
        ];
    }

    /**
     * @dataProvider invalidData
     *
     * @param mixed $data
     */
    public function testSupportsDenormalizationInvalidData($data)
    {
        $this->assertFalse($this->serializer->supportsDenormalization($data, 'foo'));
    }

    public function testSupportsDenormalizationInvalidType()
    {
        $type = 'bar';
        $data = [
            'type' => 'foo',
            'payload' => [
                'foo' => 123,
            ],
        ];

        $this->resolver
            ->expects($this->once())
            ->method('isTypeOfMessage')
            ->with($data['type'], $type)
            ->will($this->returnValue(false))
        ;

        $this->assertFalse($this->serializer->supportsDenormalization($data, $type));
    }

    public function testSupportsDenormalization()
    {
        $type = 'bar';
        $data = [
            'type' => $type,
            'payload' => [
                'foo' => 123,
            ],
        ];

        $this->resolver
            ->expects($this->once())
            ->method('isTypeOfMessage')
            ->with($data['type'], $type)
            ->will($this->returnValue(true))
        ;

        $this->assertTrue($this->serializer->supportsDenormalization($data, $type));
    }

    public function testDenormalize()
    {
        $data = [
            'type' => 'SomeMessage',
            'payload' => [
                'id' => 123,
                'name' => 'foo',
            ],
        ];

        /* @var $object \Acme_Demo_SomeMessage */
        $object = $this->serializer->denormalize($data, \Acme_Demo_SomeMessage::class);

        $this->assertInstanceOf(\Acme_Demo_SomeMessage::class, $object);
        $this->assertEquals($data['payload']['id'], $object->id);
        $this->assertEquals($data['payload']['name'], $object->name);
    }
}

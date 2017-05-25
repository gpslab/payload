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
use GpsLab\Component\Payload\Serializer\PayloadSerializer;
use GpsLab\Component\Payload\Tests\Fixture\UpdatedContactEvent;

class PayloadSerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PayloadSerializer
     */
    private $serializer;

    protected function setUp()
    {
        $this->serializer = new PayloadSerializer();
    }

    public function testSupportsNormalization()
    {
        $this->assertFalse($this->serializer->supportsNormalization(new \stdClass()));
        $this->assertTrue($this->serializer->supportsNormalization($this->getMock(Payload::class)));
    }

    public function testNotSupportsDenormalization()
    {
        $this->assertFalse($this->serializer->supportsDenormalization('', \stdClass::class));
        $this->assertFalse($this->serializer->supportsDenormalization('', Payload::class));
    }

    public function testSupportsDenormalization()
    {
        $query = new UpdatedContactEvent([
            'contact_id' => 123,
            'old_name' => 'foo',
            'new_name' => 'bar',
        ]);
        $data = UpdatedContactEvent::class.'|'.json_encode($query->payload());

        $this->assertTrue($this->serializer->supportsDenormalization($data, Payload::class));
    }

    public function testNormalize()
    {
        $query = new UpdatedContactEvent([
            'contact_id' => 123,
            'old_name' => 'foo',
            'new_name' => 'bar',
        ]);
        $data = UpdatedContactEvent::class.'|'.json_encode($query->payload());

        $this->assertEquals($data, $this->serializer->normalize($query));
    }

    public function testNormalizeCustom()
    {
        $json_options = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
        $query = new UpdatedContactEvent([
            'contact_id' => 123,
            'old_name' => 'Дмитрий Анатольевич Медведев',
            'new_name' => 'Владимир Владимирович Путин',
        ]);
        $data = UpdatedContactEvent::class.'|'.json_encode($query->payload(), $json_options);
        $serializer = new PayloadSerializer($json_options);

        $this->assertEquals($data, $serializer->normalize($query));
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\UnsupportedException
     */
    public function testDenormalizeFailed()
    {
        $this->serializer->denormalize('', Payload::class);
    }

    public function testDenormalize()
    {
        $payload = [
            'contact_id' => 123,
            'old_name' => 'Дмитрий Анатольевич Медведев',
            'new_name' => 'Владимир Владимирович Путин',
        ];
        $data = UpdatedContactEvent::class.'|'.json_encode($payload);

        $query = $this->serializer->denormalize($data, Payload::class);

        $this->assertInstanceOf(UpdatedContactEvent::class, $query);
        $this->assertEquals($payload, $query->payload());
    }

    public function testDenormalizeCustom()
    {
        $json_options = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
        $payload = [
            'contact_id' => 123,
            'old_name' => 'Дмитрий Анатольевич Медведев',
            'new_name' => 'Владимир Владимирович Путин',
        ];
        $data = UpdatedContactEvent::class.'|'.json_encode($payload, $json_options);
        $serializer = new PayloadSerializer($json_options);

        $query = $serializer->denormalize($data, Payload::class);

        $this->assertInstanceOf(UpdatedContactEvent::class, $query);
        $this->assertEquals($payload, $query->payload());
    }
}

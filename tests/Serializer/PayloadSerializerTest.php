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
use GpsLab\Component\Payload\Tests\Fixture\RenamedContactEvent;

class PayloadSerializerTest extends \PHPUnit_Framework_TestCase
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
        $query = new RenamedContactEvent([
            'contact_id' => 123,
            'old_name' => 'foo',
            'new_name' => 'bar',
        ]);
        $data = RenamedContactEvent::class.'|'.json_encode($query->payload());

        $this->assertTrue($this->serializer->supportsDenormalization($data, Payload::class));
    }

    public function testNormalize()
    {
        $query = new RenamedContactEvent([
            'contact_id' => 123,
            'old_name' => 'foo',
            'new_name' => 'bar',
        ]);
        $data = RenamedContactEvent::class.'|'.json_encode($query->payload());

        $this->assertEquals($data, $this->serializer->normalize($query));
    }

    public function testNormalizeCustom()
    {
        $json_options = JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT;
        $query = new RenamedContactEvent([
            'contact_id' => 123,
            'old_name' => 'Дмитрий Анатольевич Медведев',
            'new_name' => 'Владимир Владимирович Путин',
        ]);
        $data = RenamedContactEvent::class.'|'.json_encode($query->payload(), $json_options);
        $serializer = new PayloadNormalizer($json_options);

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
        $data = RenamedContactEvent::class.'|'.json_encode($payload);

        $query = $this->serializer->denormalize($data, Payload::class);

        $this->assertInstanceOf(RenamedContactEvent::class, $query);
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
        $data = RenamedContactEvent::class.'|'.json_encode($payload, $json_options);
        $serializer = new PayloadNormalizer($json_options);

        $query = $serializer->denormalize($data, Payload::class);

        $this->assertInstanceOf(RenamedContactEvent::class, $query);
        $this->assertEquals($payload, $query->payload());
    }
}

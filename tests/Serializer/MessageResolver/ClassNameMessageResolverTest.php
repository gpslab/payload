<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Tests\Serializer\MessageResolver;

use GpsLab\Component\Payload\Payload;
use GpsLab\Component\Payload\Serializer\MessageResolver\ClassNameMessageResolver;
use GpsLab\Component\Payload\Tests\Fixture\ContactByIdentityQuery;

class ClassNameMessageResolverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ClassNameMessageResolver
     */
    private $resolver;

    protected function setUp()
    {
        $this->resolver = new ClassNameMessageResolver();
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            [new ContactByIdentityQuery([]), 'ContactByIdentityQuery'],
            [new \Acme_Demo_SomeMessage([]), 'SomeMessage'],
        ];
    }

    /**
     * @dataProvider messages
     *
     * @param Payload $message
     * @param string  $type
     */
    public function testTypeOfMessage($message, $type)
    {
        $this->assertEquals($type, $this->resolver->typeOfMessage($message));
    }

    /**
     * @dataProvider messages
     *
     * @param Payload $message
     * @param string  $type
     */
    public function testIsTypeOfMessage($message, $type)
    {
        $this->assertTrue($this->resolver->isTypeOfMessage($type, get_class($message)));
    }
}

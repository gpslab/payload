<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Tests\Fixture;

use GpsLab\Component\Payload\PayloadDomainEvent;

class RenamedContactEvent extends PayloadDomainEvent
{
    /**
     * @var int
     */
    public $contact_id = 0;

    /**
     * @var string
     */
    public $old_name = '';

    /**
     * @var string
     */
    public $new_name = '';

    /**
     * @var int
     */
    private $hide_option = 1;
}

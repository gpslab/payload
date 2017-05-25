<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Tests\Fixture;

use GpsLab\Component\Payload\PayloadCommand;

class UpdateContactCommand extends PayloadCommand
{
    /**
     * @var int
     */
    protected $contact_id = 0;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var int
     */
    private $hide_option = 1;

    /**
     * @return int
     */
    public function contactId()
    {
        return $this->contact_id;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }
}

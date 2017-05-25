<?php

/**
 * GpsLab component.
 *
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2011, Peter Gribanov
 * @license   http://opensource.org/licenses/MIT
 */

namespace GpsLab\Component\Payload\Tests\Fixture;

use GpsLab\Component\Payload\PayloadQuery;

class ContactByIdentityQuery extends PayloadQuery
{
    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var int
     */
    private $hide_option = 1;

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }
}

<?php

namespace Tests\Models;

use App\Providers\AlertServiceProvider;
use PHPUnit\Framework\TestCase;

class AlertTest extends TestCase
{
    public function testSensorAttribute()
    {
        $alert = AlertServiceProvider::getAnAlert();
        $response = array_combine(
            array('threshold', 'type', 'deleted', 'entity', 'sensor', 'lastSent', 'alertId'),
            array("10", "0", "0", '0', '0', '20-02-2020', '0')
        );
        $this->assertEquals($response, $alert->getAttributes());
    }

    public function testGetType()
    {
        $alert = AlertServiceProvider::getAnAlert();
        $this->assertEquals("maggiore di", $alert->getType());
    }
}

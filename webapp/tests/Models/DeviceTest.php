<?php

namespace Tests\Models;

use App\Providers\DeviceServiceProvider;
use PHPUnit\Framework\TestCase;

class DeviceTest extends TestCase
{
    public function testDeviceAttribute()
    {
        $device = DeviceServiceProvider::getADevice();
        $response = array_combine(
            array('deviceId', 'name', 'frequency', 'realDeviceId'),
            array("0", "Potato", "5", "007")
        );
        $this->assertEquals($response, $device->getAttributes());
    }
}

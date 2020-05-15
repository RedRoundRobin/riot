<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['deviceId', 'name', 'frequency', 'realDeviceId', 'gateway'];
}

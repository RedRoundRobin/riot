<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewGraph extends Model
{
    protected $fillable = ['view', 'correlation', 'sensor1', 'sensor2', 'viewGraphId'];
}

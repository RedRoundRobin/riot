<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = ['threshold', 'type', 'deleted', 'entity', 'sensor', 'lastSent', 'alertId'];
    private $relType = ['maggiore di', 'minore di', 'uguale a'];


    public function getType()
    {
        return $this->relType[$this->type];
    }
}

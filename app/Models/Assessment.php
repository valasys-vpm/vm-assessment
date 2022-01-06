<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assessment extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;

    public function assessment()
    {
        return $this->hasOne(Assessment::class, 'id', 'assessment_id');
    }

}

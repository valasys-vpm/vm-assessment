<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionPaper extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;

    public function question(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}

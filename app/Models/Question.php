<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['title','exam_id'];


    public function questionImage()
    {
        return $this->hasOne(QuestionImage::class,'question_id','id');
    }

    protected $hidden = ['created_at','updated_at'];
}

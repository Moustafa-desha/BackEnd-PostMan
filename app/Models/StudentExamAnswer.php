<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExamAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['degree', 'student_exam_id', 'question_id' ];

    protected $hidden = ['created_at' ,'updated_at'];
}

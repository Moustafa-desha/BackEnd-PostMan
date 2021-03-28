<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    use HasFactory;

    protected $fillable = ['total_degree', 'student_id', 'exam_id'];

    protected $hidden = ['created_at','updated_at'];
}

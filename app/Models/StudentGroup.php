<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGroup extends Model
{
    use HasFactory;

    public function groupUser(){

        return $this->belongsTo(User::class,'student_id','id');
    }

    protected $fillable = ['student_id', 'group_id', 'count', 'price'];

    protected $hidden = ['created_at', 'updated_at' ,'student_id'];
}

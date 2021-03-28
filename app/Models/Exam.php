<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['name','start','end','time','degree','status','count','type_id','group_id','teacher_id'];


    public function studentGroups()
    {
        return $this->hasOne(studentgroup::class,'group_id','group_id');
    }

    public function examTypes()
    {
        return $this->belongsTo(ExamTypes::class,'type_id','id');
    }
}

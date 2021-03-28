<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name','is_staff'];

    public function roleUser(){
        return $this->hasMany(user::class,'role_id','id');
    }

    protected $hidden = ['created_at', 'updated_at','is_staff'];

}

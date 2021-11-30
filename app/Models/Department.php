<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;


class Department extends Model
{
    use HasFactory;
    use softDeletes;

    protected $fillable = [
        'user_id',
        'department_name'
    ];

    public function user(){
        return $this->hasOne(user::class,'id','user_id'); // join TB user กับ department โดยใช้_user  โดยใช้วิธี Eloquent 
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //mass assignment
    protected $fillable = ['name','email'];
}

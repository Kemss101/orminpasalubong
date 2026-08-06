<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\studentcontroller;
use App\Models\Student;
use Illuminate\Support\Str;


//Route ::apiResource()


Route::get('/students', [studentcontroller::class,'index']);
Route::post('/students', [studentcontroller::class,'store']);
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

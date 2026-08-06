<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class categories extends Controller
{
    //
      public function index()
    {
        $cat =Category::all();
        foreach ($cat as $cats) {
            $cats->name = ($cats->name);
            $cats->description = ($cats->description);
        }
        return view('category', compact('categorys'));
    }

}

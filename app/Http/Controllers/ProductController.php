<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;



class ProductController extends Controller
{
        public function index()
    {
   
        $products=Product::all(); 

      
        $products=Product::pluck('city');

        $products=Product::select('city','year_listed')->get();

     
        $products=Product::select('id','city')->orderBy('number_of_rooms','asc')->get();

   
        $products=Product::select('id','city')->orderBy('number_of_rooms','desc')->get();

        $products=Product::limit(5)->get();

   
        $products=Product::distinct()->pluck('city');

   
        $products=Product::where('number_of_rooms','>=',3)->get();

        $products=Product::where('number_of_rooms','>',3)->get();

        $products=Product::where('number_of_rooms','=',3)->get();

   
        $products=Product::where('number_of_rooms','<=',3)->get();

  
        $products=Product::where('number_of_rooms','<',3)->get();

        
        $products=Product::whereBetween('number_of_rooms',[3,6])->get();

       
        $products=Product::where('city','Paris')->get();

    
        $products=Product::whereIn('city', ['USA', 'France'])->get();

  
        $products=Product::where('city','like','j%')->where('city','not like','%t')->get();

     
        $product=Product::where('city', 'Paris')->where('number_of_rooms', '>',3)->get();

        $products=Product::where('city', 'Paris')->orwhere('year_listed', '>', 2012)->get();

    
        $products=Product::whereNull('number_of_rooms')->get();

        $products=Product::whereNotNull('number_of_rooms')->get();

        $totalRooms=Product::sum('number_of_rooms');

    
        $totalRooms=Product::avg('number_of_rooms');

   
        $maxListing=Product::orderBy('number_of_rooms', 'desc')->first();

    
        $minListing=Product::orderBy('number_of_rooms', 'asc')->first();

    
        $roomsByCountry = Product::select('country', DB::raw('SUM(number_of_rooms) as total_rooms'))->groupBy('country')->get();

     
        $avgRoomsByCountry = Product::select('country', DB::raw('AVG(number_of_rooms) as avg_rooms'))->groupBy('country')->get();

      
        $maxRoomsByCountry = Product::select('country', DB::raw('MAX(number_of_rooms) as max_rooms'))->groupBy('country')->get();

        
        $minRoomsByCountry = Product::select('country', DB::raw('MIN(number_of_rooms) as min_rooms'))->groupBy('country')->get();

      
        $avgRoomsByCountryavgSorted = Product::select('country', DB::raw('AVG(number_of_rooms) as avg_rooms'))->groupBy('country')->orderBy('avg_rooms', 'asc')->get();

       
        $japanUsaAvg = Product::select('country', DB::raw('AVG(number_of_rooms) as avg_rooms'))->whereIn('country', ['Japan', 'USA'])->groupBy('country')->get();

      
        $citiesPerCountry = Product::select('country', DB::raw('COUNT(DISTINCT city) as city_count'))->groupBy('country')->get();

      
        $years=Product::select('year_listed', DB::raw('COUNT(*) as total'))->groupBy('year_listed')->having('total', '>', 100)->get();

        return view('product', compact('products'));    
    } 

}

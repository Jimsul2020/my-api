<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    function index(Request $request){
        return response()->json([
            "status" => 1
        ]);
    }
}

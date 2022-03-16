<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index()
    {
        return view('images.index');
    }

    public function show()
    {
        //show all images for gallery
    }

    public function store(Request $request)
    {
        //store images
    }
}

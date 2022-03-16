<?php

namespace App\Http\Controllers;

use App\Models\Image;
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
        return Image::latest()->pluck('name')->toArray();
    }

    public function store(Request $request)
    {
        $request->validate([
           'image' => 'required|file|image|mimes:jpg,jpeg,png'
        ]);

       $path = $request->file('image')->store('public/images');

       if(!$path){
           return response()->json(['error' => 'The file could not be saved.'], 500);
       }

       $uploadedFile = $request->file('image');

        $image = Image::create([
           'name' => $uploadedFile->hashName(),
           'extension' => $uploadedFile->extension(),
           'size' => $uploadedFile->getSize()
        ]);

        return $image->name;
    }
}

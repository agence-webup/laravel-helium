<?php

namespace Webup\LaravelHelium\Blog\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Validator;
use Webup\LaravelHelium\Blog\Entities\Image;

class ImageController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image'
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $file = 'images/' . uniqid() . '.' . $request->file->extension();

        $image = Image::create(['file' => $file]);

        $path = $request->file->storeAs('public', $file);

        return response()->json(['link' => $image->url]);
    }
}

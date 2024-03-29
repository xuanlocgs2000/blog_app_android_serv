<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\URL;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    // public function saveImage($image, $path = 'public')
    // {
    //     if ($image) {
    //         # code...
    //         return null;
    //     }
    //     $filename = time() . '.png';
    //     //save image
    //     \Storage::disk($path)->put($filename, base64_decode($image));

    //     return URL::to('/') . '/storage' . $path . '/' . $filename;
    // }
    public function saveImage($image, $path = 'profiles')
    {
        if (!$image) {
            return null;
        }

        // Generate a unique filename
        $filename = time() . '_' . uniqid() . '.JPEG';

        // Save the image to the storage
        \Storage::disk('public')->put($path . '/' . $filename, base64_decode($image));

        // Return the URL to the stored image
        return URL::to('/') . '/storage/' . $path . '/' . $filename;
    }
}

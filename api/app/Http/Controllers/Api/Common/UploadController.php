<?php

namespace App\Http\Controllers\Api\Common;

use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function uploadFile()
    {
        //echo asset('storage/images/file1.txt');
        //Storage::disk('public')->put('images/file1.txt', 'Contents11');
        return Storage::download('public/file.txt');
    }
}

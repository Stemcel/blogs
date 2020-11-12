<?php

namespace App\Http\Controllers\Api\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\UserResource;

class UploadController extends Controller
{
    public function upload()
    {
        // dd(1111111);
        // Storage::disk('public')->put('file/test.txt', 'test1 file111');
        // Storage::disk('public')->append('file/test.txt', '22222');
        // return Storage::download('public\file\test.txt');
        $user = Auth::user();
        return $this->success(new UserResource($user));
    }
}

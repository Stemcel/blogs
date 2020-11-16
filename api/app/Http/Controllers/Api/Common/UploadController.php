<?php

namespace App\Http\Controllers\Api\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\UserResource;

class UploadController extends Controller
{
    //允许上传的文件格式
    public $file_allow_format = ['png', 'jpg', 'gif', 'xlsx', 'txt', 'pptx', 'docx', 'pdf', 'mp4', 'mp3'];
    
    /**
     * 文件上传
     */
    public function uploadFile(UploadRequest $request)
    {
        $result = $this->upload($request);
        if ($result['code']) {
            return $this->success($result['data']);
        } else {
            return $this->failed($result['message']);
        }
    }

    /**
     * 验证文件是否合法
     */
    public function upload($request, $disk = 'public')
    {
        $file = $request->file('source');
        $type = $request->post('type');
        // 1.是否上传成功
        if (!$file->isValid()) {
            $res_arr = [
                'code' => 0,
                'message' => '上传失败'
            ];
            return $res_arr;
        }
        // 2.是否符合文件类型 getClientOriginalExtension 获得文件后缀名
        $fileExtension = $file->getClientOriginalExtension();
        if (!in_array($fileExtension, $this->file_allow_format)) {
            $res_arr = [
                'code' => 0,
                'message' => '该文件格式不支持上传'
            ];
            return $res_arr;
        }
        // 3.判断大小是否符合 2M
        $tmpFile = $file->getRealPath();
        if (filesize($tmpFile) >= 2048000) {
            $res_arr = [
                'code' => 0,
                'message' => '资源过大'
            ];
            return $res_arr;
        }
        // 4.是否是通过http请求表单提交的文件
        if (!is_uploaded_file($tmpFile)) {
            $res_arr = [
                'code' => 0,
                'message' => '提交方式有误'
            ];
            return $res_arr;
        }
        // 5.按照分类划分一级文件夹，根据文件后缀名划分二级文件夹，日期划分三级文件夹，文件名(时间戳+随机数)
        $path = $type . '/' . $fileExtension . '/' . date('Ymd') . '/';
        $fileName = time() . mt_rand(0, 9999) . '.' . $fileExtension;
        $fileName =  $path . $fileName;
        if (Storage::disk($disk)->put($fileName, file_get_contents($tmpFile))) {
            $file_url = $request->server('HTTP_HOST') . Storage::url($fileName);
            $res_arr = [
                'code' => 1,
                'message' => '上传成功',
                'data' => $file_url
            ];
            return $res_arr;
        } else {
            $res_arr = [
                'code' => 0,
                'message' => '保存失败'
            ];
            return $res_arr;
        }
    }


    public function test(Request $request)
    {
        // dd(1111111);
        // Storage::disk('public')->put('file/test.txt', 'test1 file111');
        // Storage::disk('public')->append('file/test.txt', '22222');
        // return Storage::download('public\file\test.txt');
        // $user = Auth::user();
        // return $this->success(new UserResource($user));

        //$url = Storage::url('file.jpg');

        // $url = Storage::path('public\file\test.txt');
        // $url = Storage::temporaryUrl(
        //     'public\file\test.txt', now()->addMinutes(5)
        // );

        // $size = Storage::size('public\file\test.txt');
        // echo $size;

        //自动保存头像、文件、图片
        // $path = $request->file('avatar')->store('avatars');
        // $path = $request->file('avatar')->storeAs(
        //     'avatars', $request->user()->id
        // );
        // return $path;
        // Storage::delete('public\file\test.txt');
    }
}

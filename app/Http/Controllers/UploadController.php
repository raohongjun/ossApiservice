<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OssClass;

class UploadController extends Controller
{

    /**
     * 远程curl请求api
     * @param Request $request
     * @return string
     */
    public function curlUpload(Request $request)
    {
        if ($request->isMethod('post')) {
            $file = $request->file('upload_img');
            $upload_path = $request->get('upload_path');
            $originalname = $request->get('upload_originalname');

            if ($upload_path || $originalname) {
                $ossobj = new OssClass();
                $res = $ossobj->uploadContent($file, $file->getClientOriginalName(), $upload_path);
                //$res=$ossobj->multiuploadFile($file);
            } else {
                $ossobj = new OssClass();
                $res = $ossobj->uploadContent($file);
                //$res=$ossobj->multiuploadFile($file);
            }

            return $res;
        }
    }
}

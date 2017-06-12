<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OssClass;

class IndexController extends Controller
{


    /**
     *
     */
    protected function index()
    {
        return view('index/index');
    }

    /**
     * @param Request $request
     */
    public function upload(Request $request)
    {
        $file = $request->file('upload_img');
        $ossobj = new OssClass();
        dd($ossobj);
        //$res=$ossobj->uploadContent($file);
        $res = $ossobj->multiuploadFile($file);

    }


    /**
     * @param Request $request
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
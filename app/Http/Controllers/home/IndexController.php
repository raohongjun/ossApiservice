<?php

namespace App\Http\Controllers\home;

use App\Services\OssClass;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     *首页
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
        //$res=$ossobj->uploadContent($file);
        $res = $ossobj->multiuploadFile($file);
        return response()->json($res);
    }

}

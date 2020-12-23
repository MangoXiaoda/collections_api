<?php

namespace App\Http\Controllers;


use App\Model\cl_card;
use App\Model\cl_goods;
use App\Model\cl_images;
use Laravel\Lumen\Http\Request;
use App\Handlers\ImageUploadHandler;


class ImagesController extends Controller
{
    use V1Config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 获取图片列表接口
     * @param Request $req
     * @return mixed
     */
    private function Api_imagelist(Request $req)
    {
        $type = $req->type ?? 1; // 图片类型
        $status =  $req->status ?? 1; // 图片状态

        $list = cl_images::where('type', $type)
            ->where('status', $status)
            ->orderby('updated_at', 'desc')
            ->get()
            ->toArray();

        return api_result(200, '获取成功', $list);
    }



}

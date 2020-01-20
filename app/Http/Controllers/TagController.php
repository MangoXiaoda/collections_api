<?php

namespace App\Http\Controllers;

use App\Model\cl_tag;
use Laravel\Lumen\Http\Request;

class TagController extends Controller
{

    use V1Config;


    public function __construct()
    {

    }


    // 获取标签列表
    private function Api_taglist(Request $req)
    {
        $dev_info = $req->dev_info ?? '';
        $uid      = $req->uid ?? 0;
        $cd_id    = $req->cd_id ?? 0;
        $tag_name = $req->tag_name ?? '';

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        if (!$cd_id) {
            return api_result(202, '缺少{cd_id}参数');
        }

        $list = cl_tag::where('parentid', $cd_id)
            ->when($tag_name, function($query) use($tag_name){
                $query->where('tag_name', 'like', '%'.$tag_name.'%');
            })
            ->get()
            ->toArray();

        return api_result(200, '获取成功', $list);
    }

    // 添加标签
    private function Api_addtag(Request $req)
    {
        $dev_info = $req->dev_info ?? '';
        $uid      = $req->uid ?? 0;
        $cd_id    = $req->cd_id ?? 0;
        $tag_name = $req->tag_name ?? '';
        $order    = $req->order ?? 0;

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        if (!$cd_id) {
            return api_result(202, '缺少{cd_id}参数');
        }

        if (!$tag_name) {
            return api_result(203, '缺少标签名称');
        }

        $tag = new cl_tag();
        $tag->parentid = $cd_id;
        $tag->order = $order;
        $tag->tag_name = $tag_name;
        $tag->save();

        return api_result(200, '添加成功');
    }

    // 修改标签
    private function Api_edittag(Request $req)
    {
        $dev_info = $req->dev_info ?? '';
        $uid      = $req->uid ?? 0;
        $tg_id    = $req->tg_id ?? 0;
        $tag_name = $req->tag_name ?? '';
        $order    = $req->order ?? 0;

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        if (!$tg_id) {
            return api_result(202, '缺少{tg_id}参数');
        }

        if (!$tag_name) {
            return api_result(203, '缺少标签名称');
        }

        $data = [
            'tag_name' => $tag_name,
            'order'    => $order,
        ];

        cl_tag::where('id', $tg_id)->update($data);

        return api_result(200, '修改成功');
    }

    // 删除标签
    private function Api_deltag(Request $req)
    {
        $dev_info = $req->dev_info ?? '';
        $uid      = $req->uid ?? 0;
        $tg_id    = $req->tg_id ?? 0;

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        if (!$tg_id) {
            return api_result(202, '缺少{tg_id}参数');
        }

        cl_tag::where('id', $tg_id)->delete();

        return api_result(200, '删除成功');
    }


}

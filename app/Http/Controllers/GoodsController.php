<?php

namespace App\Http\Controllers;


use App\Model\cl_card;
use App\Model\cl_goods;
use Laravel\Lumen\Http\Request;

class GoodsController extends Controller
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
     * 获取卡片列表
     * @param Request $req
     * @return mixed
     */
    private function Api_cardlist(Request $req)
    {
        $dev_info = $req->dev_info ?? '';
        $uid      = $req->uid ?? 0;
        $cd_name  = $req->cd_name ?? '';

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        $list = cl_card::select('id','uid','dev_info','cd_name','cd_style','tag_id')
            ->when($uid, function ($query) use($uid){
                $query->where('uid',$uid);
            })
            ->when($dev_info, function ($query) use($dev_info){
                $query->where('dev_info',$dev_info);
            })
            ->when($cd_name, function ($query) use($cd_name){
                $query->where('cd_name', 'like', '%'.$cd_name.'%');
            })
            ->get()
            ->toArray();

        $data = $this->handleCardeList($list);

        return api_result(200, '获取成功', $data);
    }

    /**
     * 处理卡片列表数据
     * @param $list
     * @return array
     */
    private function handleCardeList($list)
    {
        if (!$list)
            return [];

        $str = '';
        foreach ($list as &$val){
            $str .= "{$val['cd_name']}、";
            $val['cd_style'] = CollJdecode($val['cd_style']);
        }

        $list['goods_num'] = count($list);
        $list['cd_desc'] = $str;

        return $list;
    }

    /**
     * 添加卡片
     * @param Request $req
     * @return mixed
     */
    private function Api_addcard (Request $req)
    {
        $dev_info = $req->dev_info ?? '';
        $uid      = $req->uid ?? 0;
        $cd_name  = $req->cd_name ?? '';   // 卡片名称
        $cd_style = $req->cd_style ?? '';  // 卡片样式
        $tag_id   = $req->tag_id ?? 0;       // 标签id

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        if (!$cd_name)
            return api_result(201, '请填写卡片名称');

        $card = new cl_card();
        $card->uid = $uid;
        $card->dev_info = $dev_info;
        $card->cd_name = $cd_name;
        $card->cd_style = $cd_style;
        $card->tag_id = $tag_id;

        $card->save();

        return api_result(200, '添加成功');
    }

    /**
     * 修改卡片信息
     * @param Request $req
     * @return mixed
     */
    private function Api_editcard(Request $req)
    {
        $cd_id    = $req->cd_id ?? '';     // 卡片id
        $cd_name  = $req->cd_name ?? '';   // 卡片名称
        $cd_style = $req->cd_style ?? '';  // 卡片样式
        $tag_id   = $req->tag_id ?? 0;       // 标签id

        if (!$cd_id)
            return api_result(201, '缺少{cd_id}参数');

        if (!$cd_name || !$cd_style)
            return api_result(201, '请输入修改内容');

        $data = [
            'cd_name'  => $cd_name,
            'cd_style' => $cd_style,
            'tag_id'   => $tag_id,
        ];

        cl_card::where('id',$cd_id)->update($data);

        return api_result(200, '修改成功');
    }

    /**
     * 删除卡片信息
     * @param Request $req
     * @return mixed
     */
    private function Api_delcard(Request $req)
    {
        $cd_id = $req->cd_id ?? '';     // 卡片id

        if (!$cd_id)
            return api_result(201, '缺少{card_id}参数');

        cl_card::where('id',$cd_id)->delete();

        return api_result(200, '删除成功');
    }

    /**
     * 获取物品列表
     * @param Request $req
     * @return mixed
     */
    private function Api_goodslist(Request $req)
    {
        $dev_info = $req->dev_info ?? '';
        $uid      = $req->uid ?? 0;
        $cd_id    = $req->cd_id ?? 0;

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        if (!$cd_id)
            return api_result(201, '缺少{cd_id}参数');

        $list = cl_goods::where('cd_id',$cd_id)
            ->when($uid, function ($query) use($uid){
                $query->where('uid',$uid);
            })
            ->when($dev_info, function ($query) use($dev_info){
                $query->where('dev_info',$dev_info);
            })
            ->get()
            ->toArray();

        return api_result(200, '获取成功',$list);
    }

    /**
     * 添加物品
     * @param Request $req
     * @return mixed
     */
    private function Api_addgoods(Request $req)
    {
        $dev_info        = $req->dev_info ?? '';
        $uid             = $req->uid ?? 0;
        $cd_id           = $req->cd_id ?? 0;
        $gs_name         = $req->gs_name ?? '';
        $gs_img          = $req->gs_img ?? '';
        $gs_location     = $req->gs_location ?? '';
        $gs_location_img = $req->gs_location_img ?? '';
        $gs_tag          = $req->gs_tag ?? 0;
        $gs_overtime     = $req->gs_overtime ?? 0;

        if (!$cd_id)
            return api_result(201, '缺少{cd_id}参数');

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        if (!$gs_name)
            return api_result(201, '请填写物品名称');

        $cl_goods = new cl_goods();
        $cl_goods->uid = $uid;
        $cl_goods->dev_info = $dev_info;
        $cl_goods->gs_name = $gs_name;
        $cl_goods->gs_img = $gs_img;
        $cl_goods->gs_location = $gs_location;
        $cl_goods->gs_location_img = $gs_location_img;
        $cl_goods->cd_id = $cd_id;
        $cl_goods->gs_tag = $gs_tag;
        $cl_goods->gs_overtime = $gs_overtime;
        $cl_goods->save();

        return api_result(200, "添加物品成功");
    }

    /**
     * 修改物品信息
     * @param Request $req
     * @return mixed
     */
    private function Api_editgoods(Request $req)
    {
        $gs_id           = $req->gs_id ?? 0;
        $dev_info        = $req->dev_info ?? '';
        $uid             = $req->uid ?? 0;
        $gs_name         = $req->gs_name ?? '';
        $gs_img          = $req->gs_img ?? '';
        $gs_location     = $req->gs_location ?? '';
        $gs_location_img = $req->gs_location_img ?? '';
        $gs_tag          = $req->gs_tag ?? 0;
        $gs_overtime     = $req->gs_overtime ?? 0;

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        if (!$gs_id)
            return api_result(201, '缺少{gs_id}参数');

        $data = [
            'gs_name'        => $gs_name,
            'gs_img'         => $gs_img,
            'gs_location'    => $gs_location,
            'gs_location_img'=> $gs_location_img,
            'gs_tag'         => $gs_tag,
            'gs_overtime'    => $gs_overtime
        ];

        cl_goods::where('id',$gs_id)->update($data);

        return api_result(200, '更新成功');
    }

    /**
     * 删除物品
     * @param Request $req
     * @return mixed
     */
    private function Api_delgoods(Request $req)
    {
        $gs_id     = $req->gs_id ?? 0;
        $dev_info  = $req->dev_info ?? '';
        $uid       = $req->uid ?? 0;

        if (!$gs_id)
            return api_result(201, '缺少{gs_id}参数');

        if (!$dev_info && !$uid)
            return api_result(201, '缺少{dev_info}或会员编号参数');

        cl_goods::where('id', $gs_id)->delete();

        return api_result(200, '删除成功');
    }

}

<?php

namespace App\Http\Controllers;


use App\Model\cl_card;
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
     * 添加卡片
     * @param Request $req
     * @return mixed
     */
    private function Api_addCard (Request $req) {

        $card_name = $req->card_name ?? '';   // 卡片名称
        $card_style = $req->card_style ?? ''; // 卡片样式

        if (!$card_name)
            return api_result(201, '请填写卡片名称');

        $card = new cl_card();
        $card->card_name = $card_name;
        $card->card_style = $card_style;

        $card->save();

        return api_result(200, 'success');
    }


}

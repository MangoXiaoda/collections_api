<?php

namespace App\Http\Controllers;


use Laravel\Lumen\Http\Request;

class ExampleController extends Controller
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


    private function Api_test (Request $req) {

        $data = [
            'name' => '小张',
            'age'  => '20',
            'sex'  => '男'
        ];

        return api_result(200, '获取成功',$data);
    }

    private function Api_post(Request $req)
    {
        $test = $req->test ?? 0;

        return r_result1(200, '测试post');
    }

}

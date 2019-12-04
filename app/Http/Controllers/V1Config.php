<?php
/**
 * Created by PhpStorm.
 * User: wenghuijian
 * Date: 2019/11/25
 * Time: 10:36
 */

namespace App\Http\Controllers;
use Illuminate\Http\Request;

trait V1Config
{
    protected $auth;
    protected $auth_cookie;
    protected $apiName;

    /**
     * Get请求处理
     * @param Request $req
     * @return array 结果数据
     */
    public function get(Request $req) {
        $this->SetApiData($req);
        if (!$this->apiName)
            return r_result1(301, '接口格式错误');

        $fun = 'Api_' . $this->apiName;
        if (!method_exists($this, $fun))
            return r_result1(302, '接口尚未实现，不能调用');
        return $this->$fun($req);
    }

    /**
     * post请求处理
     * @param Request $req
     * @return array 结果数组
     */
    public function post (Request $req) {
        $this->SetApiData($req);

        $fun = 'Api_' . $this->apiName;
        if (!method_exists($this, $fun))
            return r_result1(302, '接口尚未实现，不能调用');

        return $this->$fun($req);
    }

    private function SetApiData ($req) {
        $att = $req->attributes->all();
        $this->auth = $att['auth']??[];
        $this->auth_cookie = $att['auth_cookie']??'';

        $api_url = $req->getPathInfo();
        $arr = explode('/', $api_url);
        $api_name = [];
        foreach ($arr as $k => $v) {
            $v = trim($v);
            if ($v == '')
                continue;
            if ($v == 'v1')
                continue;
            $api_name[] = $v;
        }

        $this->apiName = implode('_', $api_name);
    }


}

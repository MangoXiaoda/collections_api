<?php
/**
 * Created by PhpStorm.
 * User: wenghuijian
 * Date: 2019/11/4
 * Time: 16:39
 */

/**
 * 返回数据
 * @param $code 返回码
 * @param array $data 结果数据
 * @param bool $toJson 是否返回Json 格式
 * @param string $desc 问题描述
 * @return mixed array | string  结果数据
 */
function r_result ($code, $data = [], $toJson = false, $desc = '') {
    $arr = [
        'code'  => $code,
        'data'  => $data,
        'desc'  => $desc,
    ];
    if (!$toJson)
        return $arr;

    return LexueJencode($arr);
}

/**
 * 返回结果数据
 * @param int $code 返回码
 * @param string $desc 描述
 * @param array $data 结果数据
 * @param bool $toJson 是否转换为JSON格式
 * @return mixed
 */
function r_result1 ($code, $desc = '', $data = [], $toJson = false) {
    return r_result($code, $data, $toJson, $desc);
}

/**
 * 根据返回结果，进行输出并退出
 * @param $re 返回结果
 * @param bool $withOut200 code = 200时，是否返回不退出
 */
function e_result_by_re ($re, $withOut200 = true) {
    if ($withOut200 && $re['code'] == 200)
        return;
    e_result($re['code'], $re['data'], $re['desc']);
}

/**
 * 退出并返回结果
 * @param $code 返回码
 * @param array $data 结果数据
 * @param string $desc 描述
 */
function e_result ($code, $data = [], $desc = '') {
    exit (r_result($code, $data, true, $desc));
}

/**
 * 返回数据
 * @param int $code 返回码
 * @param string $desc 描述
 * @param array $data 结果数据
 * @param bool $toJson 是否转换为JSON格式
 * @return mixed array | string  结果数据
 */
function api_result ($code, $desc = '', $data = [], $toJson = false) {
    $arr = [
        'status' => [
            'code' => $code,
            'desc' => $desc,
        ],
        'data'   => $data,
    ];
    if (!$toJson)
        return $arr;

    return LexueJencode($arr);
}

/**
 * 退出并返回结果
 * @param $code 返回码
 * @param string $desc 描述
 * @param array $data 结果数据
 */
function e_result1 ($code, $desc = '', $data = []) {
    exit (r_result($code, $data, true, $desc));
}

/**
 * 将数组转换为json 字符串
 * @param $arr 数组
 * @return string 结果字符串
 */
function LexueJencode ($arr) {
    return json_encode($arr, JSON_UNESCAPED_UNICODE);
}

function LexueJencodeNumCheck ($arr) {
    return json_encode($arr, JSON_NUMERIC_CHECK);
}

/**
 * 将json 字符串转换为数组
 * @param $str json 字符串
 * @return mixed 结果数据
 */
function LexueJdecode ($str) {
    if (is_array($str))
        return $str;
    return json_decode($str, true);
}

/**
 * 生成32位随机数
 * @param int $length
 * @return string
 */
function createNoncestr( $length = 32 )
{
    $chars ="abcdefghijklmnopqrstuvwxyz0123456789";
    $str   ="";
    for($i=0;$i<$length;$i++)
    {
        $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
    }
    return $str;
}

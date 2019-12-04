<?php

namespace App\Http\Middleware;

use Closure;

class ApiConfig
{
    private static $UcKey = 'Z5ufB630O5j2O4l97546Me931cg2xftft6Gfv8W264gek9k7J7v4j0I0Dbt5s376';

    /**
     * @var 接口类型
     */
    private $ApiType;

    /**
     * @var 接口名称
     */
    private $ApiName;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $auth_cookie = $request->auth_cookie??'';

        if ($auth_cookie) {
            $auth = $this->CheckDeCodeAuthCookie($auth_cookie);
            if (!($auth['uid']??0))
                return r_result(202, "参数错误(auth_cookie)");
        }

        $request->attributes->add([
            'auth'          => $auth??[],
            'auth_cookie'   => $auth_cookie,
        ]);

        return $next($request);
    }

    /**
     * 检测并解密令牌
     * @param string $ack 令牌
     * @return array 结果数组
     */
    private function CheckDeCodeAuthCookie ($ack) {
        if (!$ack)
            return [];
        $arr = self::DeCodeAuthCookie($ack);
        $auth = [
            'password'  => $arr[0]??'',
            'uid'       => $arr[1]??0,
            'dev'       => $arr[2]??''
        ];
        return $auth;
    }

    /**
     * 解密令牌
     * @param string $ack token
     * @return array 结果数组
     */
    private static function DeCodeAuthCookie ($ack) {
        $str = self::AuthCode($ack, 'DECODE');
        if ($str) {
            $arr = explode("\t", $str);
            if (isset($arr[1]))
                return $arr;
            $arr = explode('\t', $str);
            if (isset($arr[1]))
                return $arr;
        }

        $ack = urldecode($ack);
        $str = self::AuthCode($ack, 'DECODE');
        if (!$str)
            return [];
        $arr = explode("\t", $str);
        if (isset($arr[1]))
            return $arr;
        $arr = explode('\t', $str);
        if (isset($arr[1]))
            return $arr;
        return [];
    }

    /**
     * 字符串解密加密,解密
     * @param string $string - 要加密或解密的字符串.
     * @param string $operation - = 'ENCODE' 表示加密; = 'DECODE' 表示解密.
     * @param string $key - 加密密钥, 若未给出则使用 UC_KEY
     * @param int $expiry - ? 过期时间
     * @return string 返回加密或解密结果字符串
     * 思考: 不如将 encode, decode 分解为两个函数, 而非用参数 $operation 区分.
     */
    private static function AuthCode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
        $ckey_length = 4;	// 随机密钥长度 取值 0-32;
        // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
        // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
        // 当此值为 0 时，则不产生随机密钥

        $key = md5($key ? $key : self::$UcKey);	// 如果未给出 $key, 则使用和 UC 通信的 UC_KEY, 其与UC保持一致.
        $keya = md5(substr($key, 0, 16));	// $key 前半部分再一次 md5, $keya = 32字节长字符串
        $keyb = md5(substr($key, 16, 16));	// $key 后半部分再一次 md5, $keya = 32字节长字符串
        // $keyc 对于 'DECODE' 取 $string 的前4个字符; 对于 'ENCODE' 是取 microtime() 随机后4个字符, 说起来应该更随机.
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);		// 64 字节长字符串.
        $key_length = strlen($cryptkey);

        // 加密
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        // 下面为加密/解密过程. 具体加密算法还不太熟悉.
        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
                return substr($result, 26);
            return '';
        }

        return $keyc.str_replace('=', '', base64_encode($result));
    }
}

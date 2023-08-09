<?php
/*
 * @author: 布尔
 * @name: 钉钉Service类
 * @desc: 介绍
 * @LastEditTime: 2023-07-06 11:49:45
 */
namespace Eykj\Dtalk;

use Eykj\Base\GuzzleHttp;
use function Hyperf\Support\env;

class Service
{
    protected ?GuzzleHttp $GuzzleHttp;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp)
    {
        $this->GuzzleHttp = $GuzzleHttp;
    }

    /**
     * @author: 布尔
     * @name: 获取access_token
     * @param array $param
     * @return string
     */
    public function get_access_token(array $param): string
    {
        if (!redis()->get('baidu_access_token')) {
            /* 获取配置url */
            $r = $this->GuzzleHttp->get('https://aip.baidubce.com/oauth/2.0/token?grant_type=client_credentials&client_id='.$param['api_key'].'&client_secret='. $param['secret_key']);
            if (isset($r["error"])) {
                error(500, $r['error_description']);
            } else {
                redis()->set('baidu_access_token', $r["access_token"], $r['expires_in']);
                return $r["access_token"];
            }
        } else {
            return redis()->get('baidu_access_token');
        }
    }
}

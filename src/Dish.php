<?php
/*
 * @author: 布尔
 * @name:  菜品识别
 * @desc: 介绍
 * @LastEditTime: 2023-08-09 15:40:23
 */
namespace Eykj\Baidu;

use Eykj\Base\GuzzleHttp;
use Eykj\Baidu\Service;
use function Hyperf\Support\env;

class Dish
{
    protected ?GuzzleHttp $GuzzleHttp;

    protected ?Service $Service;

    // 通过设置参数为 nullable，表明该参数为一个可选参数
    public function __construct(?GuzzleHttp $GuzzleHttp, ?Service $Service)
    {
        $this->GuzzleHttp = $GuzzleHttp;
        $this->Service = $Service;
    }

    /**
     * @author: 布尔
     * @name: 自定义菜品-添加
     * @param array $param
     * @return array
     */
    public function add(array $param) : array
    {
        $r = $this->GuzzleHttp->post('https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/add?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'image,brief'),['headers'=>['Content-Type'=> 'application/x-www-form-urlencoded']]);
        if (isset($r["error_code"])) {
            error(500, $r['error_msg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 自定义菜品-检索
     * @param array $param
     * @return array
     */
    public function search(array $param) : array
    {
        $r = $this->GuzzleHttp->post('https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/search?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'image,url'),['headers'=>['Content-Type'=> 'application/x-www-form-urlencoded']]);
        if (isset($r["error_code"])) {
            error(500, $r['error_msg']);
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 自定义菜品-删除
     * @param array $param
     * @return array
     */
    public function delete(array $param) : array
    {
        $r = $this->GuzzleHttp->post('https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/delete?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'image,url,cont_sign'),['headers'=>['Content-Type'=> 'application/x-www-form-urlencoded']]);
        if (isset($r["error_code"])) {
            error(500, $r['error_msg']);
        }
        return $r;
    }
}
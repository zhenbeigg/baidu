<?php
/*
 * @author: 布尔
 * @name:  菜品识别
 * @desc: 介绍
 * @LastEditTime: 2024-11-08 09:15:25
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
    public function add(array $param): array
    {
        $r = $this->GuzzleHttp->post('https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/add?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'image,brief'), en_type: 'form_params');
        if (isset($r["error_code"])) {
            switch ($r["error_code"]) {
                case '216201':
                    error(500, '图片格式错误,请上传PNG、JPG、JPEG、BMP格式图片');
                    break;

                case '216203':
                    error(500, '上传的图片中包含多个主体，请上传只包含一个主体的菜品图片');
                    break;

                case '216200':
                    error(500, '上传图片未检测到菜品');
                    break;

                case '216681':
                    error(500, '上传图片已存在菜品库');
                    break;

                default:
                    error(500, '添加失败');
                    break;
            }
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 自定义菜品-检索
     * @param array $param
     * @return array
     */
    public function search(array $param): array
    {
        $r = $this->GuzzleHttp->post('https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/search?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'image,url'), en_type: 'form_params');
        if (isset($r["error_code"])) {
            error(500, '未检测到菜品信息');
        }
        return $r;
    }

    /**
     * @author: 布尔
     * @name: 自定义菜品-删除
     * @param array $param
     * @return array
     */
    public function delete(array $param): array
    {
        $r = $this->GuzzleHttp->post('https://aip.baidubce.com/rest/2.0/image-classify/v1/realtime_search/dish/delete?access_token=' . $this->Service->get_access_token($param), eyc_array_key($param, 'image,url,cont_sign'), en_type: 'form_params');
        /*返回错误码切不等于菜品不存在的报错 */
        if (isset($r["error_code"]) && $r["error_code"] != 216680) {
            error(500, '删除失败');
        }
        return $r;
    }
}

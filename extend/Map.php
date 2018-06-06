<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/28
 * Time: 16:37
 * 百度地图相关业务封装
 */
class Map {
    /**
     * 根据地址来获取经纬度
     * @param $address
     * @return mixed|string
     */
    public static function getLngLat($address)
    {
        if(!$address){
            return '';
        }
        //http://api.map.baidu.com/geocoder/v2/?address=北京市海淀区上地十街10号&output=json&ak=E4805d16520de693a3fe707cdc962045&callback=showLocation
        $data = [
            'address' => $address,
            'ak'      => config('map.ak'),
            'output'  => 'json',
        ];
        $url = config('map.baidu_map_url').config('map.geocoder').'?'.http_build_query($data);
        //第一种方法 file_get_contents($url);
        //第二种方法 curl
        $result = doCurl($url);
        return $result;
    }
    //http://api.map.baidu.com/staticimage/v2
    /**
     * 根据经纬度或者地址来获取百度地图
     * @param $center
     * @return array
     */
    public static function staticimage($center)
    {
        if(!$center){
            return '';
        }
        $data = [
            'ak'      => config('map.ak'),
            'width'   => config('map.width'),
            'height'  => config('map.height'),
            'center'  => $center,
            //'markers' => $center,
        ];
        $url = config('map.baidu_map_url').config('map.staticimage').'?'.http_build_query($data);
        //var_dump($url);
        return $url;
        //dump($url);
        //第一种方法 file_get_contents($url);
        //第二种方法 curl
        $result = doCurl($url);
        if($result){
            return json_decode($result);
        }else{
            return [];
        }
        //dump($result);
        //return $result;
    }
}
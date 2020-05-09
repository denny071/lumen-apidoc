<?php
namespace  Denny071\LaravelApidoc\Models;

use Denny071\LaravelApidoc\Helper;

use Denny071\LaravelApidoc\DocumentData;

/**
 * Module 模块
 */
class Method
{



    private static $_methodList = [
        "G" => "GET",
        "P" => "POST",
        "U" => "PUT",
        "D" => "DELETE",
    ];

    /**
     * __construct 初始化
     *
     * @param  mixed $data
     * @return void
     */
    public static function dealData(array $data)
    {

          //判断方法信息是否符合条件
        if (count($data) == 4) {
            //方法名称
            if (DocumentData::$methodName != $data[0]) {
                DocumentData::$methodName = $data[0];
            }

            //设置方法地址
            $handle = &DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName];

            $http =  env("APP_URL")."/". env("API_PREFIX",'api')."/".DocumentData::$moduleName;

            preg_match_all("/{(\w*)}/",$data[2],$content);
            if($content[1]){
                foreach($content[1] as $param){
                    $handle['params'][] = [
                        "optional" => "1",
                        "type" => "string",
                        "name" => $param,
                        "describe" => "URL参数",
                    ];
                }
            }
            $handle['http'] = $http . str_replace("index","/",DocumentData::$methodName).$data[2] ;
            //设置方法传递方式
            $handle['mode'] = self::$_methodList[DocumentData::$methodMode];
            //设置方法标题
            $handle['title'] = $data[1];
            //设置方法吗描述
            $handle['describe'] = isset($data[3]) ? $data[3] : "";

        } else {
            Helper::sendMessageJson(implode(",", $data) . "方法信息不正确");
        }
    }




}
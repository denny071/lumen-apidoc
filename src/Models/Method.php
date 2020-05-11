<?php
namespace  Denny071\LaravelApidoc\Models;

use Denny071\LaravelApidoc\Helper;

use Denny071\LaravelApidoc\DocumentData;

/**
 * Module 模块
 */
class Method
{

    /**
     *
     * 参数类型
     * _paramType
     *
     * @var array
     */
    static private $_paramType = ['S' => 'string', 'I' => 'int', 'A' => 'array'];

    /**
     * _methodList 方法列表
     *
     * @var array
     */
    static private $_methodList = [
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

            $uriList = [];
            foreach(explode("/",$data[2]) as $uri) {
                if(isset($uri{0}) && $uri{0} == "{") {
                    $content = explode("|",substr($uri,1,strlen($uri)-2)) ;
                    $handle['params'][] = [
                        "optional" => "1",
                        "type" => self::$_paramType[$content[0]],
                        "name" => $content[1],
                        "describe" => $content[2]."(URL参数)"
                    ];
                    $uriList[] = "{".$content[1]."}";
                }else {
                    $uriList[] = $uri;
                }
            }
            $http = env("APP_URL")."/". env("API_PREFIX",'api')."/";
            // 设置方法URL地址
            $handle['http'] = $http . DocumentData::$moduleName ."/". implode("/", $uriList) ;
            // 设置方法传递方式
            $handle['mode'] = self::$_methodList[DocumentData::$methodMode];
            // 设置方法标题
            $handle['title'] = $data[1];
            // 设置方法吗描述
            $handle['describe'] = isset($data[3]) ? $data[3] : "";

        } else {
            Helper::sendMessageJson(implode(",", $data) . "方法信息不正确");
        }
    }




}
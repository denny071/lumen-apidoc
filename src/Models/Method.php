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
                        "optional" => "2",
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
            // 设置Mock
            $handle['output'] = Mock::dealData();

            $handler = &DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName];
            $messagePath = resource_path(DocumentData::$moduleName).DIRECTORY_SEPARATOR.'message.php';

            if (is_file($messagePath)){
                $messageList = require $messagePath;
                self::dealConfigValidateMessage($handler,$messageList);
                self::dealConfigErrorMessage($handler,$messageList);
            }

        } else {
            Helper::sendMessageJson(implode(",", $data) . "方法信息不正确");
        }
    }

    /**
     * _dealConfigValidateMessage 处理模块内的配置文件提示信息函数
     *
     * @param  mixed $messageInfo
     * @return void
     */
    public static function dealConfigValidateMessage(&$handler,$messageList,$validateList = [])
    {
        if ($validateList) {
            $dataList = $validateList;
        } else {
            $validatePath = resource_path(DocumentData::$moduleName).DIRECTORY_SEPARATOR.'validate.php';
            if (!is_file($validatePath)){
                return false;
            }
            $validateList = require $validatePath;
            if (!isset($validateList[DocumentData::$methodName])) {
                return false;
            }
            $dataList = $validateList[DocumentData::$methodName];
        }
        foreach ($dataList as $key => $code) {
            $message = $messageList[$code];
            $condition = explode(".",$key)[1];
            if ($condition == "nullable") {
                continue;
            }
            if (strpos($condition,":")) {
                list($replaceKey,$replaceValue) = explode(":",$condition);
                $message = str_replace(":".$replaceKey,$replaceValue,$message);
            }
            $handler["info"][$code] = $message;
        }

        ksort($handler["info"]);
    }

    /**
     * _dealConfigErrorMessage 处理模块内的配置文件错误信息函数
     *
     * @param  mixed $messageInfo
     * @return void
     */
    public static function dealConfigErrorMessage(&$handler,$messageList,$errorList = [])
    {
        // 提示消息路径
        $errorPath = resource_path(DocumentData::$moduleName).DIRECTORY_SEPARATOR.'error.php';

        if (!is_file($errorPath)){
            return false;
        }
        if ($errorList) {
            $dataList = $errorList;
        } else {
            $errorList = require $errorPath;
            if (!isset($errorList[DocumentData::$methodName])) {
                return false;
            }
            $dataList = $errorList[DocumentData::$methodName];
        }

        foreach ($dataList as $code) {
            $handler["error"][$code] = $messageList[$code];
        }
        ksort($handler["error"]);
    }


    /**
     * dealConfigInput 处理输入
     *
     * @param  mixed $handler
     * @param  mixed $inputList
     * @return void
     */
    public static function dealConfigInput($inputList){

        //获得参数列表的参数信息
        foreach ($inputList as $paramInfo) {
            $param = explode("-", $paramInfo);
            if (count($param) == 3) {
                ParamValue::dealItem($param);
            }
        }
    }


}
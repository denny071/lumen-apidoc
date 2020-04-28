<?php
namespace  Denny071\LaravelApidoc\Models;


use Denny071\LaravelApidoc\DocumentData;

/**
 * Module 模块
 */
class Message
{

    /**
     *
     * @var array 错误类型
     */
    static private $_errorType = ['I' => 'info', 'E' => 'error', 'S' => 'success', 'F' => 'fail'];


    /**
     * __construct 初始化
     *
     * @param  mixed $data
     * @return void
     */
    public static function dealData(array $dataList)
    {
         //获得消息列表的消息信息
         foreach ($dataList as $message) {
            $messageInfo = explode("-", $message);
            if ($messageInfo[0] == "config:validate" && DocumentData::$messagePath) {
                self::_dealConfigValidateMessage($messageInfo[1]);
                continue;
            }
            if ($messageInfo[0] == "config:error" && DocumentData::$messagePath) {
                self::_dealConfigErrorMessage($messageInfo[1]);
                continue;
            }
            if(!in_array($messageInfo[0]{0},array_keys(self::$_errorType))){
                continue;
            }

            if (count($messageInfo) == 2) {
                //获得类型
                $type = self::$_errorType[$messageInfo[0]{0}];
                //获得编码
                $code = substr($messageInfo[0], 1);
                //设置数据
                DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName][$type][$code] = $messageInfo[1];
            }
            //构造错误码（新增）
            if (count($messageInfo) == 1) {
                //获得类型
                $type = self::$_errorType[$messageInfo[0]{0}];
                //获得编码
                $code = substr($messageInfo[0], 1);
                //设置数据
                $message=config('message')['1.0.0']['cn'];
                DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName][$type][$code] = $message[$code];
            }
        }
    }



    /**
     * _dealConfigValidateMessage 处理模块内的配置文件提示信息函数
     *
     * @param  mixed $messageInfo
     * @return void
     */
    private static function _dealConfigValidateMessage($messageInfo)
    {
        $config = require DocumentData::$messagePath;
        foreach (explode("|",$messageInfo) as $messageKey) {
            foreach ($config['validate'][$messageKey] as $key => $value) {
                list($field, $condition) = explode(".",$key);
                list($code, $message) = explode("|",$value);
                if (strpos($condition,":")) {
                    list($replaceKey,$replaceValue) = explode(":",$condition);
                    $message = str_replace(":".$replaceKey,$replaceValue,$message);
                }
                DocumentData::$documentData[DocumentData::$moduleNameKey]['method']
                [DocumentData::$methodName]["info"][$code] =$message;
            }
        }
        ksort( DocumentData::$documentData[DocumentData::$moduleNameKey]['method']
        [DocumentData::$methodName]["info"]);
    }

    /**
     * _dealConfigErrorMessage 处理模块内的配置文件错误信息函数
     *
     * @param  mixed $messageInfo
     * @return void
     */
    private static function _dealConfigErrorMessage($messageInfo)
    {
        $config = require DocumentData::$messagePath;
        foreach (explode("|",$messageInfo) as $messageKey) {
            foreach ($config['error'][$messageKey] as $code) {
                DocumentData::$documentData[DocumentData::$moduleNameKey]['method']
                [DocumentData::$methodName]["error"][$code] =$errorMessage[$code];
            }
        }
        ksort( DocumentData::$documentData[DocumentData::$moduleNameKey]['method']
        [DocumentData::$methodName]["error"]);
    }
}
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

        $handler = &DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName];
        if (is_file(DocumentData::$messagePath)){
            $messageList = require DocumentData::$messagePath;

            self::_dealConfigValidateMessage($handler,$messageList);
            self::_dealConfigErrorMessage($handler,$messageList);
        }

         //获得消息列表的消息信息
         foreach ($dataList as $message) {

            $messageInfo = explode("-", $message);

            if(!in_array($messageInfo[0]{0},array_keys(self::$_errorType))){
                continue;
            }
            if (count($messageInfo) == 2) {
                //获得类型
                $type = self::$_errorType[$messageInfo[0]{0}];
                //获得编码
                $code = substr($messageInfo[0], 1);
                //设置数据
                $handler[$type][$code] = $messageInfo[1];
            }
            //构造错误码（新增）
            if (count($messageInfo) == 1) {
                //获得类型
                $type = self::$_errorType[$messageInfo[0]{0}];
                //获得编码
                $code = substr($messageInfo[0], 1);
                //设置数据
                $message=config('message')['1.0.0']['cn'];
                $handler[$type][$code] = $message[$code];
            }
        }

    }



    /**
     * _dealConfigValidateMessage 处理模块内的配置文件提示信息函数
     *
     * @param  mixed $messageInfo
     * @return void
     */
    private static function _dealConfigValidateMessage(&$handler,$messageList)
    {
        if (!is_file(DocumentData::$validatePath)){
            return false;
        }
        $validateList = require DocumentData::$validatePath;

        foreach ($validateList[DocumentData::$methodName] as $key => $code) {
            $message = $messageList[$code];
            $condition = explode(".",$key)[1];
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
    private static function _dealConfigErrorMessage(&$handler,$messageList)
    {
        if (!is_file(DocumentData::$errorPath)){
            return false;
        }
        $errorList = require DocumentData::$errorPath;
        foreach ($errorList[DocumentData::$methodName] as $code) {
            $handler["error"][$code] = $messageList[$code];
        }
        ksort($handler["error"]);
    }
}
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
            $handler = &DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName];
            if(strstr($message, 'config:')){
                $configInfo = explode(":",$message);
                $configFile = resource_path().DIRECTORY_SEPARATOR. $configInfo[1]."_".$configInfo[0] .".php";
                if (!is_file($configFile)) {
                    continue;
                }
                $configList = require $configFile;
                Method::dealConfigValidateMessage($handler,$configList['message'],$configList['validate']);
                Method::dealConfigErrorMessage($handler,$configList['message'],$configList['error']);
                Method::dealConfigInput($configList['input']);
            }
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




}
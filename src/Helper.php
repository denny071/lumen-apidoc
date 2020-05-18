<?php

namespace Denny071\LaravelApidoc;

use Denny071\LaravelApidoc\Exception\{
    ConfigException,
    InvalidRequestException
};
use Illuminate\Support\Facades\Validator;

/**
 * Helper 辅助函数
 */
class Helper
{


    /**
     * sendDataJson 发送数据
     *
     * @param  mixed $data
     * @return string
     */
    public static function sendDataJson(array $data): string
    {
        if (env('APP_ENV') != "testing") {
            header('Content-type: application/json;charset=utf-8');
        }
        $outputData = ["state" => 0, "message" => "", "data" =>  $data];

        return json_encode($outputData, JSON_UNESCAPED_SLASHES);
    }


    /**
     * sendMessageJson 发送错误消息
     *
     * @param  mixed $message
     * @return string
     */
    public static function sendMessageJson(string $message): string
    {
        if (env('APP_ENV') != "testing") {
            header('Content-type: application/json;charset=utf-8');
        }
        $outputData = ["state" => 0, "message" => $message, "data" =>  []];

        return json_encode($outputData, JSON_UNESCAPED_SLASHES);
    }




    /**
     * checkRequest 检查请求
     *
     * @param  mixed $scene 场景
     * @param  mixed $resourceKey 资源路径
     * @param  mixed $callback 返回消息回调函数
     * @return void
     */
    public static function checkRequestByConfigFile(string $configName, $callback)
    {
        $configPath = resource_path() . DIRECTORY_SEPARATOR . $configName . "_config.php";
        if (!is_file($configPath)) {
            return request();
        }

        $configPath = require $configPath;
        if (!isset($configPath['validate'])) {
            throw new ConfigException("验证的场景错误，请查看config配置");
        }

        $rules = [];
        $messages = [];
        foreach ($configPath['validate'] as $key => $code) {
            list($field, $require)                  = explode(".", $key);
            $rules[$field][]                        = $require;
            $require                                = explode(":", $require);
            $messages[$field . "." . $require[0]]   = $configPath['message'][$code];
        }
        return self::customerValidator($rules, $messages, $configPath['message'], $callback);
    }


    /**
     * checkRequest 检查请求
     *
     * @param  mixed $scene 场景
     * @param  mixed $resourceKey 资源路径
     * @param  mixed $callback 返回消息回调函数
     * @return void
     */
    public static function checkRequest(string $moduleNanme, string $scene, $callback)
    {
        $valiatePath    = resource_path($moduleNanme) . DIRECTORY_SEPARATOR . "validate.php";
        if (!is_file($valiatePath)) {
            return request();
        }
        $data           = require $valiatePath;
        if (!isset($data[$scene])) {
            throw new ConfigException("验证的场景错误，请查看config配置");
        }
        $dataList       = $data[$scene];

        $messagePath = resource_path($moduleNanme) . DIRECTORY_SEPARATOR . "message.php";
        if (!is_file($messagePath)) {
            return app('request');
        }
        $messageList    = require $messagePath;

        if ($dataList) {
            $rules = [];
            $messages = [];
            foreach ($dataList as $key => $code) {
                list($field, $require)                  = explode(".", $key);
                $rules[$field][]                        = $require;
                $require                                = explode(":", $require);
                $messages[$field . "." . $require[0]]   = $messageList[$code];
            }
            return self::customerValidator($rules, $messages, $messageList, $callback);
        }
    }


    /**
     * customerValidator 自定义验证
     *
     * @param  mixed $rules 规则
     * @param  mixed $messages 消息
     * @param  mixed $callback 返回消息回调函数
     * @return void
     */

    public static function customerValidator($rules, $messages, $messageList, $callback)
    {
        array_walk($rules, function (&$value) {
            $value      = implode("|", $value);
            $value      = str_replace("@", ".", $value);
        });

        $validator = Validator::make(app('request')->all(), $rules, $messages);
        if ($validator->fails()) {
            $message    =   current($validator->errors()->getMessages())[0];
            $code       =   array_flip($messageList)[$message];
            return call_user_func($callback, $code, $message);
        }
        return app('request');
    }


     /**
     * getMessageByConfigFile 获得配置文件信息
     *
     * @param  mixed $configName    文件名
     * @param  mixed $code          编码
     * @return string
     */
    public static function getMessageByConfigFile(string $configName,string $code): string
    {
        $configPath = resource_path() . DIRECTORY_SEPARATOR . $configName . "_config.php";
        if (!is_file($configPath)) {
            return request();
        }
        $configPath = require $configPath;


        // 消息列表
        $messageList = $configPath['message'];
        // 判断消息不存在
        if (!$messageList) {
            return "";
        }
        // 判断消息Code不存在
        if (!isset($messageList[$code])) {
            return "";
        }
        // 输出错误消息
        return $messageList[$code];
    }

    /**
     * getMessage 错误信息
     *
     * @param  mixed $module  模块名
     * @param  mixed $method  方法名
     * @param  mixed $code    编码
     * @return string
     */
    public static function getMessage(string $module, string $code): string
    {
        // 提示消息路径
        $messagePath = resource_path($module) . DIRECTORY_SEPARATOR . 'message.php';
        // 消息文件
        if (!is_file($messagePath)) {
            return "";
        }
        // 消息列表
        $messageList = require $messagePath;
        // 判断消息不存在
        if (!$messageList) {
            return "";
        }
        // 判断消息Code不存在
        if (!isset($messageList[$code])) {
            return "";
        }
        // 输出错误消息
        return $messageList[$code];
    }
}

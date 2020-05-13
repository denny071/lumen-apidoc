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
class Helper{


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
        $outputData =["state" => 0,"message" => "", "data" =>  $data];

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
        $outputData =["state" => 0,"message" => $message, "data" =>  []];

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
    public static function checkRequestByConfigFile(string $configName,$callback)
    {
        $configPath = resource_path().DIRECTORY_SEPARATOR.$configName."_config.php";
        if(!is_file($configPath)){
            return request();
        }

        $configPath = require $configPath;
        if (!isset($configPath['validate'])) {
            throw new ConfigException("验证的场景错误，请查看config配置");
        }

        $rules = [];
        $messages = [];
        foreach ($configPath['validate'] as $key => $code) {
            list($field, $require) = explode(".", $key);
            $rules[$field][] = $require;
            $require = explode(":", $require);
            $messages[$field . "." . $require[0]] = $configPath['message'][$code];
        }
        return self::customerValidator($rules,$messages,$code,$callback);

    }


    /**
     * checkRequest 检查请求
     *
     * @param  mixed $scene 场景
     * @param  mixed $resourceKey 资源路径
     * @param  mixed $callback 返回消息回调函数
     * @return void
     */
    public static function checkRequest(string $moduleNanme,string $scene,$callback)
    {
        $valiatePath = resource_path($moduleNanme).DIRECTORY_SEPARATOR."validate.php";
        if(!is_file($valiatePath)){
            return request();
        }
        $messagePath = resource_path($moduleNanme).DIRECTORY_SEPARATOR."message.php";
        if(!is_file($messagePath)){
            return app('request');
        }

        $data = require $valiatePath;
        $messageList = require $messagePath;
        if (!isset($data[$scene])) {
            throw new ConfigException("验证的场景错误，请查看config配置");
        }
        if ($dataList = $data[$scene]) {
            $rules = [];
            $messages = [];
            foreach ($dataList as $key => $code) {
                list($field, $require) = explode(".", $key);
                $rules[$field][] = $require;
                $require = explode(":", $require);
                $messages[$field . "." . $require[0]] = $messageList[$code];
            }

            return self::customerValidator($rules,$messages,$code,$callback);
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

    public static function customerValidator($rules,$messages,$code,$callback)
    {
        array_walk($rules, function (&$value) {
            $value = implode("|", $value);
            $value = str_replace("@",".",$value);
        });

        $validator = Validator::make(app('request')->all(), $rules, $messages);
        if ($validator->fails()) {
            $result = current($validator->errors()->getMessages());
            return call_user_func($callback,$code,$result[0]);
        }
        return app('request');
    }
}


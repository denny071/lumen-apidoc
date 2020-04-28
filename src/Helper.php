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
     * 获得URI前缀
     *
     * @param string $version 版本
     * @param string $moduleName 模块名
     * @param string $methodName 方法名
     * @return string
     */
    public static function genHttpUrl($version, $moduleName, $methodName)
    {
        $url =  env("APP_URL")."/api/";
        $url .= env("API_URI_PREFIX", "");
        $url .= "v" . $version . "/";
        $url .= $moduleName . "/";
        $url .= str_replace("_", "/", $methodName);

        return $url;
    }


    /**
     * checkRequest 检查请求
     *
     * @param  mixed $scene 场景
     * @param  mixed $resourceKey 资源路径
     * @param  mixed $callback 返回消息回调函数
     * @return void
     */
    public static function checkRequest(string $scene,$resourceKey,$callback)
    {
        $resource = config("apidoc.resources")[$resourceKey];
        if (!isset($resource['message_path']) || !$resource['message_path']){
            return app('request')->all();
        }
        $data = require base_path().DIRECTORY_SEPARATOR.$resource['message_path'];
        if (!isset($data['validate'][$scene])) {
            throw new ConfigException("验证的场景错误，请查看config配置");
        }
        if ($dataList = $data['validate'][$scene]) {
            $rules = [];
            $messages = [];
            foreach ($dataList as $key => $value) {
                list($field, $require) = explode(".", $key);
                $rules[$field][] = $require;
                $require = explode(":", $require);
                $messages[$field . "." . $require[0]] = $value;
            }
            return self::customerValidator($rules,$messages,$callback);
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

    public static function customerValidator($rules,$messages,$callback)
    {
        array_walk($rules, function (&$value) {
            $value = implode("|", $value);
            $value = str_replace("@",".",$value);
        });
        $validator = Validator::make(app('request')->all(), $rules, $messages);
        if ($validator->fails()) {
            $result = current($validator->errors()->getMessages());
            list($code, $message) = explode("|", $result[0]);
            return call_user_func($callback,$code,$message);
        }
        return app('request')->all();
    }
}


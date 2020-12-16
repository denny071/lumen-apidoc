<?php

namespace Denny071\LaravelApidoc;

use Denny071\LaravelApidoc\Exception\ConfigException;
use Denny071\LaravelApidoc\Models\Mock;

/**
 * 文档数据
 *
 * DocumentData
 */
class DocumentData
{

    /**
     * documentData 文档数据
     *
     * @var array
     */
    static public $documentData = [];

    /**
     *
     * @var string 模块名称
     */
    static public $version = "";

    /**
     *
     * @var string 模块名称
     */
    static public $moduleName = "";

    /**
     *
     * @var string 模块名称key
     */
    static public $moduleNameKey = "";

    /**
     *
     * @var string 方法名称
     */
    static public $methodName = "";

    /**
     *
     * @var string 方法方法
     */
    static public $methodMode = "";




    /**
     *
     * @var array 关键字方法
     */
    static public $keyMethod = [
        'V' => 'Module',
        'G' => 'Method',
        'P' => 'Method',
        'U' => 'Method',
        'D' => 'Method',
        'A' => 'ParamValue',
        'R' => 'ReturnValue',
        'M' => 'Message',
        'I' => 'InputData',
        'F' => 'Define'
    ];


    /**
     * 获得文档数据
     * @return array 文档数据
     */
    public function __construct()
    {
        $documentFile = config('apidoc.cache.document_data');

        $cacheEnable = config('apidoc.cache.enable');

        if (file_exists($documentFile) && $cacheEnable) {
            self::$documentData = json_decode(file_get_contents($documentFile), true);
        } else {
            // 解析文件
            $this->_analyseFile(base_path().DIRECTORY_SEPARATOR.config("apidoc.router_path"));

            //写入文件
            if($cacheEnable) {
                file_put_contents($documentFile, json_encode(self::$documentData, JSON_UNESCAPED_UNICODE));
            }
        }

    }




    /**
     * 分析路由文件
     *
     * @param file $apiFile api路由文件
     * @return void
     */
    private function _analyseFile($apiFile)
    {
        //文件
        if (!is_file($apiFile)) {
            return false;
        }
        //文件内容
        $content = [];
        //查询到的类
        $subject = nl2br(file_get_contents($apiFile));
        //匹配路由信息
        if (!preg_match_all('/\/\/@(.*?)<br/', $subject, $content)) {
            return;
        }


        //获得单个路由路由新
        foreach ($content[1] as $route) {
            // 判断是否为列表
            if(in_array($route[0], ["V","G","P","U","D","F"])){

                self::$methodMode = $route[0];
                $param = explode("-", substr($route, 1));

            }else {
                $param = explode(",", substr($route, 2, strlen($route) - 3));
            }
            ("\\Denny071\\LaravelApidoc\\Models\\".self::$keyMethod[$route[0]])::dealData($param);
        }


    }



}

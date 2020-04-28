<?php

namespace Denny071\LaravelApidoc;

use Denny071\LaravelApidoc\Exception\ConfigException;

use Denny071\LaravelApidoc\Models\{
    Module,
    MethodGet,
    MethodPost,
    ParamValue,
    ReturnValue,
    Message,
    InputData
};

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
     * @var string 参数类型
     */
    static public $paramType = "";


    /**
     *
     * @var string 资源地址
     */
    static public $resourcePath = "";

      /**
     *
     * @var string 资源地址
     */
    static public $messagePath = "";

      /**
     *
     * @var string 资源地址
     */
    static public $mockDir = "";


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
            //生成缓存数据
            $this->_startDealData();
            //写入文件
            if($cacheEnable) {
                file_put_contents($documentFile, json_encode(self::$documentData, JSON_UNESCAPED_UNICODE));
            }
        }
    }


    /**
     * 生成缓存数据文件
     */
    private function _startDealData()
    {
        $resources = config("apidoc.resources");
        if (!$resources) {
            throw new ConfigException("resources not setting");
        }
        if (!is_array($resources)) {
            throw new ConfigException("resources not is array");
        }

        foreach ($resources as $resource) {
            // mock数据目录
            self::$mockDir = isset($resource['mock_dir'])?base_path().DIRECTORY_SEPARATOR.$resource['mock_dir']:"";
            // 文档数据资源路径
            self::$resourcePath = base_path().DIRECTORY_SEPARATOR.$resource['resource_path'];
            // 提示消息路径
            self::$messagePath = isset($resource['message_path'])?base_path().DIRECTORY_SEPARATOR.$resource['message_path']:"";
            // 解析文件
            $this->_analyseFile(self::$resourcePath);
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

            switch ($route[0]) {
                case "V":
                    Module::dealData(explode("-", substr($route, 1)));
                    break;
                case "G":
                    self::$methodMode = "GET";
                    MethodGet::dealData(explode("-", substr($route, 1)));
                    break;
                case "P":
                    self::$methodMode = "POST";
                    MethodPost::dealData(explode("-", substr($route, 1)));
                    break;
                case "A":
                    ParamValue::dealData(explode("-", substr($route, 1)));
                    break;
                case "R":
                    ReturnValue::dealData(explode(",", substr($route, 2, strlen($route) - 3)));
                    break;
                case "M":
                    Message::dealData(explode(",", substr($route, 2, strlen($route) - 3)));
                    break;
                case "I":
                    InputData::dealData(explode(",", substr($route, 2, strlen($route) - 3)));
                    break;
            }
        }
    }
}

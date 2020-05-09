<?php

namespace Denny071\LaravelApidoc;

use Denny071\LaravelApidoc\Exception\ConfigException;

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
     * @var string 路由地址
     */
    static public $routerPath = "";

    /**
     *
     * @var string 消息文件地址
     */
    static public $messagePath = "";

    /**
     *
     * @var string 错误消息文件地址
     */
    static public $errorPath = "";

    /**
     *
     * @var string 输入验证文件地址
     */
    static public $validatePath = "";

      /**
     *
     * @var string mock目录地址
     */
    static public $mockDir = "";

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
        $modules = config("apidoc.modules");
        if (!$modules) {
            throw new ConfigException("modules not setting");
        }
        if (!is_array($modules)) {
            throw new ConfigException("modules not is array");
        }

        foreach ($modules as $module) {
            // mock数据目录
            self::$mockDir = resource_path($module).DIRECTORY_SEPARATOR.'mock';
            // 提示消息路径
            self::$messagePath = resource_path($module).DIRECTORY_SEPARATOR.'message.php';
            // 提示消息路径
            self::$errorPath = resource_path($module).DIRECTORY_SEPARATOR.'error.php';
            // 提示消息路径
            self::$validatePath = resource_path($module).DIRECTORY_SEPARATOR.'validate.php';
        }
         // 文档数据资源路径
         self::$routerPath = base_path().DIRECTORY_SEPARATOR.config("apidoc.router_path");
         // 解析文件
         $this->_analyseFile(self::$routerPath);
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
            if(in_array($route[0], ["V","G","P","U","D"])){
                self::$methodMode = $route[0];
                $param = explode("-", substr($route, 1));
            }else {
                $param = explode(",", substr($route, 2, strlen($route) - 3));
            }
            ("\\Denny071\\LaravelApidoc\\Models\\".self::$keyMethod[$route[0]])::dealData($param);
        }
    }
}

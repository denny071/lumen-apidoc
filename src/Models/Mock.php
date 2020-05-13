<?php
namespace  Denny071\LaravelApidoc\Models;

use Denny071\LaravelApidoc\DocumentData;

/**
 * Module 模块
 */
class Mock
{

    /**
     * __construct 初始化
     *
     * @param  mixed $data
     * @return void
     */
    public static function dealData()
    {
        $outputJsonFilePath = resource_path(DocumentData::$moduleName).DIRECTORY_SEPARATOR.'mock' . DIRECTORY_SEPARATOR .
                              "V" .DocumentData::$version. DIRECTORY_SEPARATOR . DocumentData::$methodName . ".json";
        if (file_exists($outputJsonFilePath)) {
            return file_get_contents($outputJsonFilePath);
        } else {
            return "";
        }
    }

}
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
        if(!DocumentData::$mockDir){
            return "";
        }
        $outputJsonFilePath = DocumentData::$mockDir . DIRECTORY_SEPARATOR . "V" .
                DocumentData::$version . DIRECTORY_SEPARATOR . DocumentData::$moduleName."_".DocumentData::$methodName . ".json";
        $content = "";
        if (file_exists($outputJsonFilePath)) {
            $content = file_get_contents($outputJsonFilePath);
        }
        return $content;

    }

}
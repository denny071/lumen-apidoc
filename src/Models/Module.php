<?php
namespace  Denny071\LaravelApidoc\Models;

use Denny071\LaravelApidoc\DocumentData;
use Denny071\LaravelApidoc\Helper;

/**
 * Module 模块
 */
class Module
{

    /**
     * __construct 初始化
     *
     * @param  mixed $data
     * @return void
     */
    public static function dealData(array $data)
    {
           //判断模块信息是否符合条件
           if (count($data) >= 3) {
            //获取接口版本
            if (DocumentData::$version != $data[0]) {
                DocumentData::$version = $data[0];
            }
            //获取模块名称
            if (DocumentData::$moduleName != $data[1]) {
                DocumentData::$moduleName = $data[1];
            }
            if (DocumentData::$moduleNameKey != DocumentData::$moduleName . "v" . DocumentData::$version) {
                DocumentData::$moduleNameKey = DocumentData::$moduleName . "v" . DocumentData::$version;
            }
            $handle = &DocumentData::$documentData[DocumentData::$moduleNameKey];
            //设置接口版本
            $handle['version'] = $data[0];
            //设置接口标题
            $handle['title'] = $data[2];
            //设置接口描述
            $handle['describe'] = isset($data[3]) ? $data[3] : "";
        } else {
            Helper::sendMessageJson("模块信息不正确");
        }
    }



}
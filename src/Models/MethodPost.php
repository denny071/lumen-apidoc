<?php

namespace  Denny071\LaravelApidoc\Models;

use Denny071\LaravelApidoc\Helper;

use Denny071\LaravelApidoc\DocumentData;

/**
 * Module 模块
 */
class MethodPost
{




    /**
     * __construct 初始化
     *
     * @param  mixed $data
     * @return void
     */
    public static function dealData(array $data)
    {
        //判断方法信息是否符合条件
        if (count($data) == 3) {
            //方法名称
            if (DocumentData::$methodName != $data[0]) {
                DocumentData::$methodName = $data[0];
            }
            $handle = &DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName];
            //设置方法地址
            $handle['http'] = Helper::genHttpUrl(DocumentData::$version, DocumentData::$moduleName, DocumentData::$methodName);
            //设置方法传递方式
            $handle['mode'] = DocumentData::$methodMode;
            //设置方法标题
            $handle['title'] = $data[1];
            //设置方法吗描述
            $handle['describe'] = isset($data[2]) ? $data[2] : "";
        } else {
            Helper::sendMessageJson(implode(",", $data) . "方法信息不正确");
        }
    }
}

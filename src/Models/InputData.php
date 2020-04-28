<?php

namespace Denny071\LaravelApidoc\Models;

use Denny071\LaravelApidoc\DocumentData;

/**
 * InputData 请求输入示例
 */
class InputData
{

    /**
     * __construct 初始化
     *
     * @param  mixed $data
     * @return void
     */
    public static function dealData(array $inputList)
    {
        $dataList = [];
        //获得消息列表的消息信息
        if (DocumentData::$methodMode == "GET") {
            //获得消息列表的消息信息
            foreach ($inputList as $input) {
                $dataList[] = str_replace(['\'', '"'], "", $input);
            }
            $queryString = $dataList ? ("?" . implode("&", $dataList)) : "";
            DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName]['input'] = $queryString;
        } else {
            //获得消息列表的消息信息
            foreach ($inputList as $input) {
                $inputInfo = explode("=", $input);
                $dataList[] = '"' . $inputInfo[0] . '" : ' . $inputInfo[1];
            }
            //设置句柄
            $handle = &DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName]['input'];
            if (isset($handle)) {
                //合并之前的数据
                $handle = array_merge($handle, $dataList);
            } else {
                //合并之前的数据
                $handle = $dataList;
            }
        }
    }
}

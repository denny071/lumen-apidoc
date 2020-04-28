<?php
namespace  Denny071\LaravelApidoc\Models;

use Denny071\LaravelApidoc\Helper;

use Denny071\LaravelApidoc\DocumentData;

use Denny071\LaravelApidoc\Models\Mock;

/**
 * Param 模块
 */
class ReturnValue
{

    /**
     * __construct 初始化
     *
     * @param  mixed $data
     * @return void
     */
    public static function dealData(array $dataList)
    {
         //获得返回列表的返回信息
         foreach ($dataList as $returnInfo) {
            $data = [];
            $returnInfo = explode("-", $returnInfo);
            if (count($returnInfo) == 2) {
                //设置名称
                $data['name'] = $returnInfo[0];
                //设置描述
                $data['describe'] = $returnInfo[1];
                //设置返回数据
                DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName]['return'][] = $data;
            }
        }
        DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName]['output'] = Mock::dealData();
    }

}
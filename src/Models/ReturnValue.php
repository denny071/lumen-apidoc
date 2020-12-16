<?php
namespace  Denny071\LaravelApidoc\Models;


use Denny071\LaravelApidoc\DocumentData;


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
        $handler = &DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName];
         //获得返回列表的返回信息
         foreach ($dataList as $returnInfo) {

            if ($returnInfo == "created"){
                $handler['return_state'] = "201";
                continue;
            }

            if ($returnInfo == "noContent"){
                $handler['return_state'] = "204";
                continue;
            }

            $returnInfo = explode("-", $returnInfo);
            switch (count($returnInfo)) {
                case 1:
                    $handler['return'] = DocumentData::$documentData["define"][$returnInfo[0]];
                    break;
                case 2:
                    //设置名称
                    $data['name'] = $returnInfo[0];
                    //设置描述
                    $data['describe'] = $returnInfo[1];
                    //设置返回数据
                    $handler['return'][] = $data;
                    break;
            }


        }


    }

}
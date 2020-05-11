<?php
namespace  Denny071\LaravelApidoc\Models;

use Denny071\LaravelApidoc\Helper;

use Denny071\LaravelApidoc\DocumentData;

/**
 * Param 模块
 */
class ParamValue
{


    /**
     *
     * @var array 参数模型
     */
    static private $_paramType = ['S' => 'string', 'I' => 'int', 'A' => 'array'];

    /**
     * __construct 初始化
     *
     * @param  mixed $data
     * @return void
     */
    public static function dealData(array $dataList)
    {
        //获得参数列表的参数信息
        foreach ($dataList as $paramInfo) {
            $data = [];
            $param = explode("-", $paramInfo);
            if (count($param) == 3) {
                //判断参数是否可选
                $data['optional'] = ($param[0]{0} == "?") ? 0 : 1;
                //参数类型
                $data['type'] = ($param[0]{0} == "?") ? self::$_paramType[$param[0]{1}] : self::$_paramType[$param[0]{0}];
                //参数名称
                $data['name'] = $param[1];
                //参数描述
                $data['describe'] = $param[2];
                //设置参数数据
                DocumentData::$documentData[DocumentData::$moduleNameKey]['method'][DocumentData::$methodName]['params'][] = $data;
            }
        }

    }

}
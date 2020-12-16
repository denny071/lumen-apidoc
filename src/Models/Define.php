<?php

namespace Denny071\LaravelApidoc\Models;

use Denny071\LaravelApidoc\DocumentData;

/**
 * InputData 请求输入示例
 */
class Define
{

    static $prefix = "";
    static $handle;

    /**
     * __construct 初始化
     *
     * @param  mixed $data
     * @return void
     */
    public static function dealData($data)
    {
        $dataString = trim($data[0]);
        $item = self::dealItem($dataString);

        if(count($item) == 1) {
            self::$prefix = "";
            self::$handle = &DocumentData::$documentData['define'][$item[0]];
        } else {
            list($name,$describe) = self::dealItem($dataString);
            if (substr($name, -1, 1) == ":") {
                self::$prefix = $describe."(".substr($name, 0, strlen($name) -1).")";
            } else {
                if (self::$prefix != "") {
                    self::$handle[] = ["name" => $name,"describe" => self::$prefix."@".$describe ];
                } else {
                    self::$handle[] = ["name" => $name,"describe" => $describe ];
                }
            }
        }
    }

    public static function dealItem($dataString){
         $data = [];
         foreach(explode(" ",$dataString) as $val) {
            if($val != "") {
                $data[] = trim($val);
            }
         }
         return $data;
    }
}

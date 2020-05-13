<?php

namespace Denny071\LaravelApidoc;

use Denny071\LaravelApidoc\Exception\ConfigException;

/**
 * 文档数据
 *
 * DocumentData
 */
class DocumentHtml
{

    /**
     * _cssFileList css 列表
     *
     * @var array
     */
    private $_cssFileList = [
        "bootstrap.min.css",
        "bootstrap-theme.min.css",
        "docs.min.css",
        "patch.css",
    ];

    /**
     * _jsFileList js列表
     *
     * @var array
     */
    private $_jsFileList = [
        "ie-emulation-modes-warning.js",
        "jquery.min.js",
        "bootstrap.min.js",
        "patch.css",
        "docs.min.js",
        "layer.js"
    ];


    /**
     * download 下载文件
     *
     * @return void
     */
    public function download()
    {

        new DocumentData();
        //新的文档数据
        $documentData = [];
        //根据请求类型判断文档数据
        $version = $_REQUEST['version'] ?? 1;;
        foreach (DocumentData::$documentData as $key => $data) {
            if ($data['version'] == $version) {
                $documentData[$key] = $data;
            }
        }
        $content =  view("apidoc::document", [
            "title" =>  config('apidoc.cache.title', "API文档") . "-V" . $version,
            "dataList" => $documentData,
            "version" => $version,
            "model" => $_REQUEST['model'] ?? ""
        ]);
        // 替换CSS
        foreach ($this->_cssFileList as $cssFile) {
            $content = $this->_getCssContent($cssFile, $content);
        }
        // 替换JS
        foreach ($this->_jsFileList as $jsFile) {
            $content = $this->_getJsContent($jsFile, $content);
        }
        // 隐藏按钮
        $content = $this->_hideButton($content);
        // 下载文件
        $this->_downloadFile($content, $version);
    }

    /**
     * _hideButton 隐藏按钮
     *
     * @param  mixed $content
     * @return void
     */
    private function _hideButton($content) : string
    {

        $replaceContent = ".download_css{display: none !important;}";

        $search = '.download_css{display: block !important;}';

        return str_replace($search, $replaceContent, $content);
    }

    /**
     * _getCssContent 获得css内容
     *
     * @param  mixed $filename
     * @param  mixed $content
     * @return string
     */
    private function _getCssContent(string $filename, string $content): string
    {
        $styleContent = "<style>" . file_get_contents(__DIR__ . "/../resources/assets/document/" . $filename) . "</style>";

        $search = '<link href="/document/' . $filename . '" rel="stylesheet">';

        return str_replace($search, $styleContent, $content);
    }


    /**
     * _getJsContent 获得css内容
     *
     * @param  mixed $filename
     * @param  mixed $content
     * @return string
     */
    private function _getJsContent(string $filename, string $content): string
    {
        $styleContent = "<script>" . file_get_contents(__DIR__ . "/../resources/assets/document/" . $filename) . "</script>";

        $search = '<script src="/document/' . $filename . '"></script>';

        return str_replace($search, $styleContent, $content);
    }



    /**
     * _downloadFile 下载文件
     *
     * @param  mixed $content
     * @param  mixed $version
     * @return void
     */
    private function _downloadFile(string $content, string $version)
    {
        $filename = config('apidoc.cache.title', "API文档") . "-V" . $version . ".html";
        Header("Content-type:application/octet-stream");
        Header("Accept-Ranges:bytes");
        Header("Accept-Length:");
        header("Content-Disposition:  attachment;  filename= " . $filename);
        echo $content;
        exit;
    }



}

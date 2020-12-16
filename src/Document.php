<?php


namespace Denny071\LaravelApidoc;


class Document
{

    /**
     * The Laravel apidoc version.
     *
     * @var string
     */
    const VERSION = '1.0.0';


    /**
     * 生成文档
     *
     * @return void
     */
    public function genDocument()
    {

        new DocumentData();
        //新的文档数据
        $documentData = [];
        //根据请求类型判断文档数据
        $version = $_REQUEST['version']?? 1; ;
        foreach (DocumentData::$documentData as $key => $data) {
            if ($key != "define" && $data['version'] == $version) {
                $documentData[$key] = $data;
            }
        }

        return  view("apidoc::document", [
            "title" =>  config('apidoc.cache.title',"API文档")."-V".$version,
            "dataList" => $documentData,
            "version" => $version,
            "model" => $_REQUEST['model']??""
        ]);

    }


    /**
     * 清空缓存
     *
     * @return void
     */
    public function clear()
    {
        $documentData = config('apidoc.cache.document_data');

        if(is_file($documentData)) unlink($documentData);

        echo Helper::sendMessageJson("success");
    }

    /**
     * manual 文档
     *
     * @return void
     */
    public function manual()
    {

        $Parsedown = new \Parsedown();

        $markdownFile = file_get_contents(__DIR__."/../docs/manual.md");

        $content = $Parsedown->text($markdownFile);

        return view('apidoc::manual',["content" => $content]);
    }

}
<?php

return [
    "title" => "API文档",

    // 缓存
    "cache" => [
        // 是否启用缓存
        "enable" => false,
        // 数据缓存文件
        "document_data" => storage_path('app').DIRECTORY_SEPARATOR."DocData",
    ],
    "router_path" => "routes/web.php",
    // 文档uri
    "router_prefix" => "apidoc",
    // 是否开启https
    "https" => true,

];

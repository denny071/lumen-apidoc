<?php

return [
    "title" => "API文档",
    // 说明文档语言
    "language" => "zh",
    // 缓存
    "cache" => [
        // 是否启用缓存
        "enable" => false,
        // 数据缓存文件
        "document_data" => storage_path('app').DIRECTORY_SEPARATOR."DocData",
    ],
    // 资源路径
    "resources" => [
        "news" =>[
                "resource_path" => "modules/News/Routes/api.php",
                // 选填
                "mock_dir" => "modules/News/mock",
                // 选填
                "message_path" => "modules/News/Config/config.php",
        ]
    ],
    // 文档uri
    "router_prefix" => "apidoc",
    // 是否开启https
    "https" => true,

];

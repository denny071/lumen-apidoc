# 开发文档 

## 更新日志
```

日期 2020-04-27
1、在config.php 中添加 parameter 字段，用于API文档传入参数生成 [章节1.2]

日期 2019-09-17
初始化文档内容

```

> 描述：
> 基于最新版5.7一些升级，为方便模块部署与接口文档生成所做的说明。
> lumen官方详细中文文档说明连接：https://learnku.com/docs/lumen/5.7


##  集成request和document的config.php

>  将request中的配置及document的错误信息配置集成到 moduels目录下的 config.php中统一管理



** {moduleName}/config.php 的文件示例 ** 

```

<?php

   //请求提示信息
   $errorMessage["100001"] = "权限令牌必须传入";
   $errorMessage["100002"] = "权限令牌不能超过:max个字符";
   $errorMessage["100003"] = "乘务员工号必须传入";
   $errorMessage["100004"] = "设备ID长度不能超过:max个字符";
   $errorMessage["100005"] = "设备ID必须传入";
   .......
   
   //错误信息 建议人工换行 确认入参验证及错误验证
   $errorMessage["100101"] = "账号密码不正确";
   .......
   
   $validateErrorMessage = [];
   foreach ($errorMessage as $key => $value) {
       $validateErrorMessage[$key] = $key."|".$value;
   }
   
   return [
       //module名称
       'name' => 'Crew',
       //模块配置信息  调用方法   $systemConfig = config("crew.moduleConfig");
       "moduleConfig" => [
           // 短信模板 
           'messageTemplate' => env("MESSAGE_TEMPLATE","...."),
           // 送货模板
           ......       
       ],
       // 方便公共函数调用
       "errorMessage" => $errorMessage,
       // API 文档显示的错误码
       // 方法明名 => ["错误码1","错误码2"]
       "error" => [
           // 登录模块
           "login" => ["100101","100105","100110","100201"],
           // 发送短信模块
          .......
       ],
       // 参数说明 在路由中不需要添加 A标识， 系统自动识别
         "parameter" => [
             // 字段名称 => "类型|说明"
             "accessToken" => "string|授权令牌",
             "deviceType" => "string|设备类型（1:安卓 2:iOS）",
             ......
           ],
       // API 文档显示的验证信息 及 request的rule配置
       // 路由名称 => ["验证字段.验证条件"=>"提示信息"]
       "validate" => [
   
           "checkToken" =>[
               "accessToken.nullable" => '',   //当字段可选的时候，要添加这行
               "accessToken.required" => $validateErrorMessage["100001"],
                ......
           ],
       ]
   ];

```


**  routes.php配置说明 ** 

> **在`modules/模块名/routes.php`中**

> 新增目的主要是使用验证器，不用重新定义和引入Request验证器类，并且可直接生成文档

```
    ........
    //@Gorder-订单详情-订单详情
删除-> //A[S-token-令牌]  因为在config配置，所以自动生成    
    //@R[code-订单编号，goodsName-商品名称]
添加-> //@M[config:error-order,config:validate-checkToken|order]   
    Route::get('/order', 'PlaneController@order');
     ........

```

> 说明：

> `config:error-orderList `引用config.php中返回错误码。

> `config:validate-checkToken|orderList`引用config.php中验证器，并显示验证失败的错误码多个验证器用”|“分割。




## 后台新增日志查看功能

>  点击右上角查看日志按钮即可

![img](https://static.dingtalk.com/media/lALPDgQ9rAGh--XNA4_NBbE_1457_911.png_620x10000q90g.jpg)

## 生成环境api文档不可见
>  生成环境如需关闭api文档为不可见,只需修改`.env` APP_ENV=APP_ENV=product

## 异常状态处理

![img](https://static.dingtalk.com/media/lALPDgQ9rAGki1nMis0ClA_660_138.png_620x10000q90g.jpg)

> 说明：
>
> 业务异常使用 `BusinessException.php`
>
> 代码内部异常使用`InternalException.php`
>
> 无效验证异常使用`InvalidException.php`


## 开发要求


> ** .env不要上传到git 如果配置有增加或修改则在.env_example中编辑 再复制为.env使用 **

> ** 脚本执行文件建议定义在`modules/模块名/Console` 下 **

> ** 数据库迁移建议写在模块中的`migration`下 **

> ** 对外部模块执行操作建议使用观察者模式 **

> 例如 `modules/crew/Observers`中

```
<?php
/**
 * Created by PhpStorm.
 * User: denny
 * Date: 2019-08-22
 * Time: 14:15
 */

namespace Modules\Crew\Observers;


use Illuminate\Support\Facades\Log;
use Modules\Orders\Models\Orders;
use GatewayClient\Gateway as GatewayClient;

/**
 * Class OrderObserver
 * @package Modules\Crew\Observers
 */
class OrderObserver
{

    public function saved(Orders $order)
    {
     
        try{
          // todo
        }catch (\Exception $exception){
        
            Log::error($message);
            return;
        }

    }
}

```

`modules/模块名/Providers/模块名ServiceProvider.php`服务提供者中注册

```
<?php

namespace Modules\Crew\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Crew\Observers\OrderObserver;

/**
 * Class CrewServiceProvider
 * @package Modules\Crew\Providers
 */
class CrewServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {

       ......
        // 判断订单模块的订单模型是否存在，如果存在监听它
        if (class_exists("\Modules\Orders\Models\Orders")) {
            \Modules\Orders\Models\Orders::observe(OrderObserver::class);
        }
        .....

    }

   .........
}

```


##  URL路由名称隐藏模块方法


> 在{moduleName}/routes.php 中的module名前加入`_`, 如//@V1-`_`order-订单模块

```
Route::group([ 'middleware'=>'API','prefix' => 'api'], function() {

    Route::group(['prefix' => 'v1'], function()
    {
            //@V1-_customer-客户模块
            
            //@GgetCustomerList-在线客户列表-从机上redius数据库获取在线客户列表
            //@A[S-pageNo-页码,S-pageSize-每页显示条数]
            //@R[statusCode-状态码：0成功，]
            //@R[userName-用户名,seatNo-座位号]
            //@M[config:validate-getCustomerList]
            Route::get('/getCustomerList', 'CustomerController@getList');

    });
});
```


# docker 使用说明

## 简介
> 封装的镜像名称为 denny071/php_env 

> 当前版本为 `1.1`

## 更新日志
```

1.0 2019-09-12 集成 nginx 和 php 开发环境，加入composer工具
1.1 2019-09-17 添加 /start.sh 负责启动容器内的任务
```

## 部署命令

> 创建网络

```
docker network create -d bridge ms_boss_agent
```


```ssh
sudo docker run -d -p 7001:80   
    --privileged \
    --name ms_boss_agent -it \
    --network=ms_boss_agent  \
    -v /var/services/ms_boss_agent/src:/usr/share/nginx/html \
    -v /var/services/ms_boss_agent/conf/nginx.conf:/etc/nginx/nginx.conf:ro \
    -v /var/services/ms_boss_agent/logs:/var/log/nginx \
    denny/php_env:1.1  /start.sh
```

| 参数 | 说明 | 示例|
|-----|----|-----|
|privileged |使container内的root拥有真正的root权限 |--privileged |
|name |容器名称 |--name ms_boss_agent|
|it |后台运行 |-it|
|network |服务网络 |--network=ms_boss_agent |
|v |路径映射 本地路径:容器路径 |-v /var/services/ms_boss_agent/logs:/var/log/nginx |
|/start.sh |执行脚本 ||

## docker目录结构
```ssh
|- services                 # 容器
|-----ms_boss_agent         # 服务名称  
|---------conf              # 配置目录 如 nginx.conf
|---------logs              # 日志目录 如 error.log(nginx 错误日志)
|---------src               # 项目源码

```

# 知识点

** [数据库迁移](https://learnku.com/docs/laravel/5.7/migrations/2291 "数据库迁移") **

** [数据填充](https://learnku.com/docs/laravel/5.7/seeding/2292 "数据填充") **

** [module创建command](https://nwidart.com/laravel-modules/v4/advanced-tools/module-console-commands "module创建command") **

** [module创建event](https://nwidart.com/laravel-modules/v4/advanced-tools/registering-module-events "module创建event") **




<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>中文文档</title>
        <link href="/document/bootstrap.min.css" rel="stylesheet">
        <link href="data:text/css;charset=utf-8," data-href="/document/bootstrap-theme.min.css" rel="stylesheet" id="bs-theme-stylesheet">
        <link href="/document/docs.min.css" rel="stylesheet">
        <link href="/document/patch.css" rel="stylesheet">
        <script src="/document/ie-emulation-modes-warning.js"></script>
        <script src="/document/jquery.min.js"></script>
        <script src="/document/bootstrap.min.js"></script>
        <script src="/document/docs.min.js"></script>
        <script src="/document/layer.js"></script>
        <style>
            .download_css{display: block !important;}
        </style>
    </head>

    <body>
        <a id="skippy" class="sr-only sr-only-focusable" href="#content"><div class="container"><span class="skiplink-text">Skip to main content</span></div></a>
        <!-- Docs master nav -->
        <header class="navbar navbar-static-top bs-docs-nav" id="top" style="position: fixed;top: 0;left: 0;border-bottom-color:#eee;width: 100%">
            <div class="">

                <nav id="bs-navbar" class="collapse navbar-collapse ">
                    <ul class="nav navbar-nav">
                        <li style="padding: 10px;font-weight: 500;color: #6f42c1;font-size: 22px;">{{$title}}</li>
                        <li  class="@if($version == 1) active @endif download_css"><a href="{{route("apiDoc",["version" => 1])}}">第一版</a></li>
                        <li class="@if($version == 2) active @endif download_css" ><a href="{{route("apiDoc",["version" => 2])}}">第二版</a></li>
                    </ul>
                    <div class="download_css"  style="float: right;margin: 8px">
                        <input type="text" class="search_box form-control"  placeholder="快速查找" style="display:inline;padding:2px 5px;width:200px;height:35px;"/>
                        <input class="btn btn-default"  id="download" type="button" value="离线下载">
                        <input class="btn btn-default"  id="logs" type="button" value="查看日志">
                        <input class="btn btn-default"  id="manual" type="button" value="开发手册">
                        <input class="btn btn-default" id="clearCache" type="button" value="清除缓存">
                        <input class="btn btn-default" style="background-color: {{$model=='mock'?"#28a745":"#6f42c1"}} ;color: #fff" id="mockTest" type="button" value="MOCK">
                    </div>
                </nav>
                    <div class="progress-indicator" style="position: fixed;top: 50px;left: 0;height: 2px;background-color: #6f42c1;"></div>
            </div>

        </header>
@php
$defaultRespone = <<<EOF
{
    "state": 0,
    "message": "提交成功",
    "data": [] //单条数据返回{},多条数据返回[],默认为{}
}
EOF;
@endphp
        <div class="bs-docs-container" style="margin-top: 50px">
            <div class="row" style="margin:0px">
                <div  role="complementary" style="position:fixed; width:300px">
                    <nav class="bs-docs-sidebar hidden-print hidden-xs hidden-sm"  style="overflow: auto; height: 700px; width: 300px" >
                        <ul class="nav bs-docs-sidenav">
                            @php $i= 1 @endphp
                            @foreach($dataList as $className => $class)
                            <li>
                                <a href="#{{$className}}">{{$i++}}. {{$class['title']}}</a>
                                <ul class="nav">
                                    @php $j= 1 @endphp
                                    @foreach($class['method'] as $methodName => $method)
                                    <li><a href="#{{$className}}-{{$methodName}}">{{$i}}.{{$j++}} {{$method['title']}}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>
                <div  role="main" style="margin-left:320px;margin-right:10%;">
                    @foreach($dataList as $className => $class)

                    <div class="bs-docs-section">

                        <h1 class="page-header">{{$class['title']}}</h1>
                        <a id="{{$className}}" style="display: block;" ></a>
                        @foreach($class['method'] as $methodName => $method)
                        <a id="{{$className}}-{{$methodName}}" style="display: block;" > </a>
                        <div class="content" style="margin-top: 80px">
                        <h2 >{{$method['title']}}</h2>

                        @if($method['describe'] != "")
                        <p>接口说明：{{$method['describe']}}</p>
                        @endif

                        <p>访问地址： <code >{{$method['http']}}</code></p>
                        <p>请求方式：<code>{{$method['mode']}}</code>
                            <input class="btn btn-primary btn-xs show_text download_css" rel="{{$className}}-{{$methodName}}-test-box"
                                   type="button" value="{{$model=='mock'?"mock测试":"在线测试"}}" style="background-color: {{$model=='mock'?"#28a745":"#6f42c1"}};
                                    border-color:{{$model=='mock'?"#28a745":"#6f42c1"}}; color: #fff;float: right">
                        </p>

                              <style>
                            .bs-example.my-bs-test::after{
                                content: "测试API："!important;
                            }
                        </style>
                        <div class="bs-example my-bs-test" id="{{$className}}-{{$methodName}}-test-box" style="display:none">

                                <form class="form-horizontal" id="{{$className}}-{{$methodName}}-test-form">
                                    <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <code id="{{$className}}-{{$methodName}}-test-url">{{$method['http']}}@if($model == "mock")?&model=mock @endif </code>
                                    </div>
                                  </div>
                                       <div class="form-group" id="{{$className}}-{{$methodName}}-request-data" style="display:none">
                                  <label  class="col-sm-2 control-label">传入数据</label>
                                    <div class="col-sm-offset-2 col-sm-10">
                                         <pre ></pre>
                                    </div>
                                  </div>

                                     @if(isset($method['params']))
                                        @foreach($method['params'] as $param)
                                        <div class="form-group">
                                          <label for="{{$className}}-{{$methodName}}-{{$param['name']}}" class="col-sm-2 control-label">{{$param['name']}}</label>
                                          <div class="col-sm-10">
                                            <input type="text" class="form-control" name="{{$param['name']}}" id="{{$className}}-{{$methodName}}-{{$param['name']}}"  >
                                          </div>
                                        </div>
                                       @endforeach
                                    @endif
                                        <div class="form-group">
                                          <div class="col-sm-offset-2 col-sm-10">
                                            <button type="button" class="btn btn-default" id="{{$className}}-{{$methodName}}-test-btn">测试</button>
                                          </div>
                                        </div>
                                </form>
                               <script>


                                   $(function(){
                                       var  {{$className}}_{{$methodName}}_map = {
                                            @if(isset($method['params']))
                                              @foreach($method['params'] as $param)
                                                {{$param['name']}}:"",
                                                @endforeach
                                             @endif
                                       };
                                        @if($method['mode'] == "GET")
                                       //设置输入框
                                       $("#{{$className}}-{{$methodName}}-test-form input").bind("keyup",function(){
                                           {{$className}}_{{$methodName}}_map[$(this).attr("name")] = $(this).val();
                                           var http_request_url = "{{$method['http']}}?"+Object.keys({{$className}}_{{$methodName}}_map || {})
                                                   .filter((key)=>{{$className}}_{{$methodName}}_map[key])
                                                   .map((key)=>key + '=' + {{$className}}_{{$methodName}}_map[key])
                                                   .join('&')
                                           if("{{$model}}" == "mock"){
                                             http_request_url += "&model=mock";
                                            }
                                           $("#{{$className}}-{{$methodName}}-test-url").text(http_request_url);
                                       });

                                       $("#{{$className}}-{{$methodName}}-test-btn").bind("click",function(){
                                                $.get($("#{{$className}}-{{$methodName}}-test-url").text(),{},function(data){
                                                    if(data == 1){
                                                        layer.open({
                                                            type: 1,
                                                            maxmin: true,
                                                            area: ['500px', '500px'],
                                                            content:  $("<div>")
                                                                .append($("<div>")
                                                                    .css("white-space", 'pre')
                                                                    .css("margin", '20px')
                                                                    .text($('#{{$className}}_{{$methodName}}_outputinfo').text())).html()
                                                        });
                                                    }else{
                                                        layer.open({
                                                            type: 1,
                                                            maxmin: true,
                                                            area: ['500px', '500px'],
                                                            content:  $("<div>")
                                                                .append($("<div>")
                                                                    .css("white-space", 'pre')
                                                                    .css("margin", '20px')
                                                                    .text(JSON.stringify(data, null, 4))).html()
                                                        });
                                                    }
                                                },"json");
                                       });
                                          @else
                                               //设置输入框
                                       $("#{{$className}}-{{$methodName}}-test-form input").bind("keyup",function(){
                                           {{$className}}_{{$methodName}}_map[$(this).attr("name")] = $(this).val();

                                           if($(this).attr("name") == "accessToken"){
                                                var http_request_url = "{{$method['http']}}?"+Object.keys({{$className}}_{{$methodName}}_map || {})
                                                        .filter((key)=>(key=="accessToken"))
                                                        .filter((key)=>{{$className}}_{{$methodName}}_map[key])
                                                        .map((key)=>key + '=' + {{$className}}_{{$methodName}}_map[key])
                                                        .join('&')
                                                if("{{$model}}" == "mock"){
                                                 http_request_url += "&model=mock";
                                                }
                                                $("#{{$className}}-{{$methodName}}-test-url").html(http_request_url);
                                           }else{
                                                var http_request_data = Object.keys({{$className}}_{{$methodName}}_map || {})
                                                       .filter((key)=>(key!="accessToken"))
                                                        .filter((key)=>{{$className}}_{{$methodName}}_map[key])
                                                        .map((key)=> "&nbsp;&nbsp;&nbsp;"+key + ':' + {{$className}}_{{$methodName}}_map[key])
                                                        .join(",\n")
                                                $("#{{$className}}-{{$methodName}}-request-data").show();
                                                $("#{{$className}}-{{$methodName}}-request-data pre").html("{\n"+http_request_data+"\n}");
                                           }
                                       });
                                        $("#{{$className}}-{{$methodName}}-test-btn").bind("click",function(){
                                            var requestString = {{$className}}_{{$methodName}}_map;
                                            if(requestString.hasOwnProperty('accessToken')){
                                                delete requestString.accessToken;
                                            }
                                            var ajax_url = $("#{{$className}}-{{$methodName}}-test-url").text();
                                            var requestdata = JSON.stringify(requestString);
                                            if("{{$model}}" == "mock"){
                                             ajax_url += "&model=mock";
                                            }
                                            $.ajax({
                                                type: "post",
                                                dataType: 'json',
                                                url: ajax_url,
                                                contentType: 'application/json',
                                                data: requestdata,
                                                success: function (data) {
                                                     layer.open({
                                                            type: 1,
                                                            maxmin: true,
                                                            area: ['400px', '500px'],
                                                            content: $("<div>").append($("<div>")
                                                                .css("white-space", 'pre')
                                                                .css("margin", '20px')
                                                                .text(JSON.stringify(data, null, 4)))
                                                                .html()
                                                       });

                                                }
                                              });
                                       })
                                         @endif
                                   });
                               </script>
                           </div>
                        @if(isset($method['input']))
                        <style>
                            .bs-example.my-bs-input::after{
                                content: "请求实例："!important;
                            }
                        </style>
                            @if($method['mode'] == "GET")
                            <div class="bs-example my-bs-input">
                                <code >{{$method['http'].$method['input']}}</code>
                            </div>
                            @else
                            <div class="bs-example my-bs-input" id="{{$className}}_{{$methodName}}_egurl">
                                <code>{{$method['http']}}</code>
                                <div style="margin:20px"></div>
                                <?php
                                    $inputdata = $method['input'];
                                ?>
                                @foreach($inputdata as $inputkey => $input)
                                @if(substr($input,0,13) == '"accessToken"')
                                <script>
                                $(function(){
                                    $('#{{$className}}_{{$methodName}}_egurl > code').append("?accessToken=" + '{{substr($input,17,-1)}}');
                                });
                                </script>
                                <?php
                                    unset($inputdata[$inputkey]);
                                ?>
                                @endif
                                @endforeach
                                <pre>
{
@foreach($inputdata as $input)
    {{$input}},
@endforeach
}</pre>

                            </div>
                            @endif
                        @endif
                        @if(isset($method['params']))
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <caption>请求参数</caption>
                                <thead>
                                    <tr>
                                        <th  style="width:20%">参数名</th>
                                        <th  style="width:20%">类型</th>
                                        <th  style="width:20%">是否必填</th>
                                        <th style="width:40%">说明</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($method['params'] as $param)
                                    <tr class="{{$param['optional']==1?"":"info"}}">
                                        <td>{{$param['name'] . ($param['name']=="accessToken"?" (GET传递)":"")}}</td>
                                        <td>{{$param['type']}}</td>
                                        <td>{{$param['optional']==1?"是":"否"}}</td>
                                        <td>{{$param['describe']}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        <style scoped>
                            .bs-example.my-bs-output::after{
                                content: "响应实例："!important;
                            }
                        </style>
                            <div class="bs-example my-bs-output">
                                <input class="btn btn-primary btn-xs show_text {{$className}}_{{$methodName}}_outputinfo" type="button" value="展开" onclick="showpre('{{$className}}_{{$methodName}}_outputinfo')" style="background-color: #6f42c1;color: #fff;float: right;margin-top:-30px;">
                            <div style="margin:20px"></div>

                            <pre id="{{$className}}_{{$methodName}}_outputinfo" style="height:120px">{{$method['output']??$defaultRespone}}</pre>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <caption>公共响应参数</caption>
                                <thead>
                                    <tr>
                                        <th  style="width:20%">参数名</th>
                                        <th  style="width:20%">类型</th>
                                        <th  style="width:60%">说明</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>statusCode</td>
                                        <td>int</td>
                                        <td>状态码</td>
                                    </tr>
                                    <tr>
                                        <td>statusMessage</td>
                                        <td>string</td>
                                        <td>提示信息</td>
                                    </tr>
                                    <tr>
                                        <td>data</td>
                                        <td>array</td>
                                        <td>返回数据</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        @if(isset($method['return']))
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
                                <caption>响应参数</caption>
                                <thead>
                                    <tr>
                                        <th  style="width:20%">参数名</th>
                                        <th  style="width:80%">说明</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($method['return'] as $return)
                                    <tr>
                                        <td>{{$return['name']}}</td>
                                        <td>{{$return['describe']}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        @endif
                        @if(isset($method['success']) || isset($method['fail']) || isset($method['info']) || isset($method['error']))
                        {{-- <h4>业务码</h4> --}}
                        @endif
                         @if(isset($method['success']))
                        <div class="bs-callout bs-callout-success" id="callout-tables-responsive-overflow" style="margin-bottom: 0;">
                            <table class="table table-bordered table-striped table-condensed">
                                <caption>成功编码</caption>
                                <thead>
                                    <tr>
                                        <th style="width:20%">编码</th>
                                        <th style="width:80%">说明</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($method['success'] as $code => $message)
                                    <tr>
                                        <td>{{$code}}</td>
                                        <td>{{$message}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                         @if(isset($method['info']))
                             <div class="bs-callout bs-callout-info" id="callout-tables-responsive-overflow" style="margin-bottom: 0;">
                                 <table class="table table-bordered table-striped table-condensed">
                                     <caption>请求响应信息</caption>
                                     <thead>
                                     <tr>
                                         <th style="width:20%">编码</th>
                                         <th>说明</th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                     @foreach($method['info'] as $code => $message)
                                         <tr>
                                             <td>{{$code}}</td>
                                             <td>{{$message}}</td>
                                         </tr>
                                     @endforeach
                                     </tbody>
                                 </table>
                             </div>
                         @endif
                        @if(isset($method['fail']))
                         <div class="bs-callout bs-callout-danger" id="callout-tables-responsive-overflow" style="margin-bottom: 0;">
                            <table class="table table-bordered table-striped table-condensed">
                                <caption>错误编码</caption>
                                <thead>
                                    <tr>
                                        <th style="width:30%">状态码</th>
                                        <th style="width:40%">描述</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($method['fail'] as $code => $message)
                                    <tr>
                                        <td>{{$code}}</td>
                                        <td>{{$message}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                         </div>
                        @endif
                        @if(isset($method['error']))
                        <div class="bs-callout bs-callout-danger" id="callout-tables-responsive-overflow" style="margin-bottom: 0;">
                            <table class="table table-bordered table-striped table-condensed">
                                <caption>错误编码</caption>
                                <thead>
                                    <tr>
                                        <th style="width:20%">编码</th>
                                        <th>说明</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($method['error'] as $code => $message)
                                    <tr>
                                        <td>{{$code}}</td>
                                        <td>{{$message}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endif
                        </div>
                        @endforeach

                     </div>
                    @endforeach
                </div>
            </div>
        </div>
    </body>
</html>
<script>
    function changeURLArg(arg,arg_val){
        replaceText = arg+"="+arg_val;
        origin =window.location.origin;
        pathname =window.location.pathname;
        search =window.location.search;

        hash =window.location.hash;
        if(search){
            return origin+pathname+search+'&'+replaceText+hash;
        }else{
            return origin+pathname+search+'?'+replaceText+hash;
        }

    }
    $("#mockTest").click(function () {
        @if($model == "mock")
            window.location.href = changeURLArg("model", "");
        @else
            window.location.href = changeURLArg("model", "mock");
        @endif

    });


$(function(){




    $("#manual").click(function () {
        var index = layer.open({
                title:"开发手册",
                type: 2,
                maxmin: true,
                shade:0,
                area: ['1200px', '700px'],
                content:"{{route('manual')}}"
           });
        layer.full(index);
    });



   $("#logs").click(function () {
       var index =  layer.open({
                title:"系统日志",
                type: 2,
                maxmin: true,
                shade:0,
                area: ['1200px', '700px'],
                content:"{{route('logs')}}"
           });
       layer.full(index);
    });



    $("#download").click(function () {
        window.location.href = "{{route('download')}}";
    });


    $("#clearCache").click(function () {
       $.get("{{route('apiClear')}}","",function (data) {
           if(data.statusCode== 0) {
               layer.msg('清除缓存成功', {
                   icon: 1,
                   time: 2000 //2秒关闭（如果不配置，默认是3秒）
               }, function(){
                   location.reload();
               });
           }
       },"json")
    });

    $(".show_text").click(function(){
      var id = $(this).attr("rel");
      $("#"+id).toggle(1000);
    })
    $.ajaxSetup({
        layerIndex:-1,
        beforeSend: function () {
            this.layerIndex = layer.msg('处理中,请稍等', {
                                  icon: 16
                                  ,shade: 0.01
                                });
        },
        complete: function () {
            layer.close(this.layerIndex);
        },
        error: function () {
            layer.alert('部分数据加载失败，可能会导致页面显示异常，请刷新后重试', {
                skin: 'layui-layer-molv'
               , closeBtn: 0
               , shift: 4 //动画类型
            });
        }
    });

    $(".search_box").keyup(function(){
        var txt=$("input[type=text]").val();
        if($.trim(txt)!=""){
            $(".content").hide().filter(":contains('"+txt+"')").show();
        } else {
            $(".content").show()
        }
    });
      (function() {
        var $w = $(window);
        var $prog2 = $('.progress-indicator');
        var wh = $w.height();
        var h = $('body').height();
        var sHeight = h - wh;
        $w.on('scroll', function() {
            window.requestAnimationFrame(function(){
            var perc = Math.max(0, Math.min(1, $w.scrollTop() / sHeight));
            updateProgress(perc);
            });
        });

        function updateProgress(perc) {
            $prog2.css({width: perc * 100 + '%'});

        }

    }());
})
function showpre(id){
    if($("."+id).val()=="展开"){
        $("#"+id).removeAttr("style");
        $("."+id).val("收缩");
    }else{
        $("#"+id).attr("style","height:120px;");
        $("."+id).val("展开");
    }

}
</script>

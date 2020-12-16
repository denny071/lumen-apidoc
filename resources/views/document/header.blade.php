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
<script>
$(function(){

    // 快速查找
    $(".search_box").keyup(function(){
        var txt=$("input[type=text]").val();
        if($.trim(txt)!=""){
            $(".content").hide().filter(":contains('"+txt+"')").show();
        } else {
            $(".content").show()
        }
    });
      // 离线下载
    $("#download").click(function () {
        window.location.href = "{{route('download')}}";
    });
    // 开发手册
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
    // 查看日志
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
    // 清除缓存
    $("#clearCache").click(function () {
       $.get("{{route('apiClear')}}","",function (data) {

           if(data.state== 0) {
               layer.msg('清除缓存成功', {
                   icon: 1,
                   time: 1000
               }, function(){
                   location.reload();
               });
           }
       },"json")
    });

    // 进度条
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

});
</script>
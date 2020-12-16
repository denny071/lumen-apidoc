
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
            @include("apidoc::document.header", ['title' => $title,'model' => $model])
        </header>

        <div class="bs-docs-container" style="margin:50px 0">
            <div class="row" style="margin:0px">
                <div  role="complementary" style="position:fixed; width:300px">
                 @include("apidoc::document.side", ['dataList' => $dataList])
                </div>

                <div  role="main" style="margin-left:320px;margin-right:10%;">
                    @foreach($dataList as $className => $class)
                    @include("apidoc::document.module", ['className' => $className,'class' => $class])
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

$(function(){
    // mockTest按钮
    $("#mockTest").click(function () {
        @if($model == "mock")
            window.location.href = changeURLArg("model", "");
        @else
            window.location.href = changeURLArg("model", "mock");
        @endif
    });
    $(".show_test").click(function(){
      var id = $(this).attr("rel");
      if($(this).val()=="隐藏"){
            $("#"+id).slideUp(1000)
            $(this).val("在线测试");
        }else{
            $("#"+id).slideDown(1000)
            $(this).val("隐藏");
        }
    })
    $(".show_output").click(function(){
      var id = $(this).attr("rel");
      if($(this).val()=="展开"){
            $("#"+id).removeAttr("style");
            $(this).val("收缩");
        }else{
            $("#"+id).attr("style","height:120px;");
            $(this).val("展开");
        }
    })
    //
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


})

</script>

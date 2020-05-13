<div class="bs-example my-bs-test" id="{{$className}}-{{$methodName}}-test-box" style="display:none">
    <form class="form-horizontal" id="{{$className}}-{{$methodName}}-test-form">
        <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <code id="{{$className}}-{{$methodName}}-test-url" style="width: 100%">{{$method['http']}}@if($model == "mock")?&model=mock @endif </code>
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
            <label for="{{$className}}-{{$methodName}}-{{$param['name']}}"
            class="col-sm-2 control-label {{$param['optional']=="2"?"text-danger":""}}">{{$param['name']}}</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" name="{{$param['name']}}" alt='{{$param['optional']}}' id="{{$className}}-{{$methodName}}-{{$param['name']}}"  >
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
</div>
<script>
$(function(){
    @php
        if(isset($method['params'])) {
            $body = [];
            $param = [];
            foreach($method['params'] as $value){
                if($value['optional']=="2"){
                    $param[] = $value['name'].":''";
                } else {
                    $body[] = $value['name'].":''";
                }
            }
            $bodyString = implode(",",$body);
            $paramString = implode(",",$param);
        }
    @endphp
    var {{$className}}_{{$methodName}}_data = {
        param : {{!!$paramString!!}},
        body : {{!!$bodyString!!}},
        url_string : "{{$method['http']}}",
        param_string : ""

    }
    @if($method['mode'] == "GET" )
        //设置输入框
        $("#{{$className}}-{{$methodName}}-test-form input").bind("keyup",function(){
            if($(this).attr("alt") == "2") {
                {{$className}}_{{$methodName}}_data['param'][$(this).attr("name")] = $(this).val();
                Object.getOwnPropertyNames({{$className}}_{{$methodName}}_data['param']).forEach(function(key){
                    if({{$className}}_{{$methodName}}_data['param'][key]) {
                        {{$className}}_{{$methodName}}_data['url_string'] = "{{$method['http']}}".replace("{"+key+"}",{{$className}}_{{$methodName}}_data['param'][key])
                    }
                })
            } else {
                {{$className}}_{{$methodName}}_data['body'][$(this).attr("name")] = $(this).val();
                {{$className}}_{{$methodName}}_data['param_string'] = "?"+Object.keys({{$className}}_{{$methodName}}_data['body'] || {})
                    .filter((key)=>{{$className}}_{{$methodName}}_data['body'][key])
                    .map((key)=>key + '=' + {{$className}}_{{$methodName}}_data['body'][key])
                    .join('&')
                if("{{$model}}" == "mock"){
                    param_string += "&model=mock";
                }
            }
            $("#{{$className}}-{{$methodName}}-test-url").text({{$className}}_{{$methodName}}_data['url_string'] + {{$className}}_{{$methodName}}_data['param_string']);
        });
        $("#{{$className}}-{{$methodName}}-test-btn").bind("click",function(){
            $.get($("#{{$className}}-{{$methodName}}-test-url").text(),{},function(data){
                if(data == 1){
                    layer.open({
                        type: 1,
                        maxmin: true,
                        area: ['500px', '500px'],
                        content:  $("<div>").append($("<div>").css("white-space", 'pre').css("margin", '20px')
                                .text($('#{{$className}}_{{$methodName}}_outputinfo').text())).html()
                    });
                }else{
                    layer.open({
                        type: 1,
                        maxmin: true,
                        area: ['500px', '500px'],
                        content:  $("<div>").append($("<div>").css("white-space", 'pre').css("margin", '20px')
                                .text(JSON.stringify(data, null, 4))).html()
                    });
                }
            },"json");
        });
    @endif
    @if($method['mode'] == "POST" )
        //设置输入框
        $("#{{$className}}-{{$methodName}}-test-form input").bind("keyup",function(){
            if($(this).attr("alt") == "2") {
                {{$className}}_{{$methodName}}_data['param'][$(this).attr("name")] = $(this).val();
                Object.getOwnPropertyNames({{$className}}_{{$methodName}}_data['param']).forEach(function(key){
                    if({{$className}}_{{$methodName}}_data['param'][key]) {
                        {{$className}}_{{$methodName}}_data['url_string'] = "{{$method['http']}}".replace("{"+key+"}",{{$className}}_{{$methodName}}_data['param'][key])
                    }
                })
                if("{{$model}}" == "mock"){
                    {{$className}}_{{$methodName}}_data['url_string'] += "&model=mock";
                }
                $("#{{$className}}-{{$methodName}}-test-url").text({{$className}}_{{$methodName}}_data['url_string'] + {{$className}}_{{$methodName}}_data['param_string']);
            } else {
                {{$className}}_{{$methodName}}_data['body'][$(this).attr("name")] = $(this).val();
                {{$className}}_{{$methodName}}_data['param_string'] = Object.keys({{$className}}_{{$methodName}}_data['body'] || {})
                        .filter((key)=>{{$className}}_{{$methodName}}_data['body'][key])
                        .map((key)=> "&nbsp;&nbsp;&nbsp;"+key + ':' + {{$className}}_{{$methodName}}_data['body'][key])
                        .join(",\n")
                $("#{{$className}}-{{$methodName}}-request-data").show();
                $("#{{$className}}-{{$methodName}}-request-data pre").html("{\n"+{{$className}}_{{$methodName}}_data['param_string']+"\n}");
            }

        });
        $("#{{$className}}-{{$methodName}}-test-btn").bind("click",function(){
            var requestString = {{$className}}_{{$methodName}}_data['body'];
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
                            content: $("<div>").append($("<div>").css("white-space", 'pre').css("margin", '20px')
                                .text(JSON.stringify(data, null, 4))).html()
                        });
                },
                error: function (data) {
                    if(data.status == 201) {
                        layer.msg("创建或更新成功")
                    }
                }
            });
        })
    @endif


    @if($method['mode'] == "PUT" )
        //设置输入框
        $("#{{$className}}-{{$methodName}}-test-form input").bind("keyup",function(){
            var http_request_url = "{{$method['http']}}";
            if($(this).attr("alt") == "2") {
                {{$className}}_{{$methodName}}_data['param'][$(this).attr("name")] = $(this).val();
                Object.getOwnPropertyNames({{$className}}_{{$methodName}}_data['param']).forEach(function(key){
                    if({{$className}}_{{$methodName}}_data['param'][key]) {
                        {{$className}}_{{$methodName}}_data['url_string'] = "{{$method['http']}}".replace("{"+key+"}",{{$className}}_{{$methodName}}_data['param'][key])
                    }
                })
                if("{{$model}}" == "mock"){
                    {{$className}}_{{$methodName}}_data['url_string'] += "&model=mock";
                }
                $("#{{$className}}-{{$methodName}}-test-url").text({{$className}}_{{$methodName}}_data['url_string'] + {{$className}}_{{$methodName}}_data['param_string']);
            } else {
                {{$className}}_{{$methodName}}_data['body'][$(this).attr("name")] = $(this).val();
                {{$className}}_{{$methodName}}_data['param_string'] = Object.keys({{$className}}_{{$methodName}}_data['body'] || {})
                        .filter((key)=>{{$className}}_{{$methodName}}_data['body'][key])
                        .map((key)=> "&nbsp;&nbsp;&nbsp;"+key + ':' + {{$className}}_{{$methodName}}_data['body'][key])
                        .join(",\n")
                $("#{{$className}}-{{$methodName}}-request-data").show();
                $("#{{$className}}-{{$methodName}}-request-data pre").html("{\n"+{{$className}}_{{$methodName}}_data['param_string']+"\n}");
            }

        });
        $("#{{$className}}-{{$methodName}}-test-btn").bind("click",function(){
            var requestString = {{$className}}_{{$methodName}}_data['body'];
            var ajax_url = $("#{{$className}}-{{$methodName}}-test-url").text();
            var requestdata = JSON.stringify(requestString);
            if("{{$model}}" == "mock"){
                ajax_url += "&model=mock";
            }
            $.ajax({
                type: "put",
                dataType: 'json',
                url: ajax_url,
                contentType: 'application/json',
                data: requestdata,
                success: function (data) {

                        layer.open({
                            type: 1,
                            maxmin: true,
                            area: ['400px', '500px'],
                            content: $("<div>").append($("<div>").css("white-space", 'pre').css("margin", '20px')
                                .text(JSON.stringify(data, null, 4))).html()
                        });
                },
                error: function (data) {
                    if(data.status == 201) {
                        layer.msg("创建或更新成功")
                    }

                    if(data.status == 500) {
                        layer.alert(data.responseJSON.message)
                    }
                }
            });
        })
    @endif


    @if($method['mode'] == "DELETE")
        //设置输入框
        $("#{{$className}}-{{$methodName}}-test-form input").bind("keyup",function(){

            if($(this).attr("alt") == "2") {
                {{$className}}_{{$methodName}}_data['param'][$(this).attr("name")] = $(this).val();
                Object.getOwnPropertyNames({{$className}}_{{$methodName}}_data['param']).forEach(function(key){
                    if({{$className}}_{{$methodName}}_data['param'][key]) {
                        {{$className}}_{{$methodName}}_data['url_string'] = "{{$method['http']}}".replace("{"+key+"}",{{$className}}_{{$methodName}}_data['param'][key])
                    }
                })
            } else {
                {{$className}}_{{$methodName}}_data['body'][$(this).attr("name")] = $(this).val();
                {{$className}}_{{$methodName}}_data['param_string'] = "?"+Object.keys({{$className}}_{{$methodName}}_data['body'] || {})
                    .filter((key)=>{{$className}}_{{$methodName}}_data['body'][key])
                    .map((key)=>key + '=' + {{$className}}_{{$methodName}}_data['body'][key])
                    .join('&')
                if("{{$model}}" == "mock"){
                    param_string += "&model=mock";
                }
            }

            $("#{{$className}}-{{$methodName}}-test-url").text({{$className}}_{{$methodName}}_data['url_string'] + {{$className}}_{{$methodName}}_data['param_string']);
        });
        $("#{{$className}}-{{$methodName}}-test-btn").bind("click",function(){
            var requestString = {{$className}}_{{$methodName}}_data['body'];
            var ajax_url = $("#{{$className}}-{{$methodName}}-test-url").text();
            var requestdata = JSON.stringify(requestString);
            if("{{$model}}" == "mock"){
                ajax_url += "&model=mock";
            }
            $.ajax({
                type: "delete",
                dataType: 'json',
                url: ajax_url,
                contentType: 'application/json',
                data: requestdata,
                error: function (data) {
                    if(data.status == 201) {
                        layer.msg("创建或更新成功")
                    }
                    if(data.status == 500) {
                        layer.alert(data.responseJSON.message)
                    }
                }
            });
        })
    @endif
});
</script>

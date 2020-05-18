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
                @if($param['type'] == "file")
                <div class="form-group">
                <label for="{{$className}}-{{$methodName}}-{{$param['name']}}"
                class="col-sm-2 control-label {{$param['optional']=="2"?"text-danger":""}}">{{$param['name']}}</label>
                    <div class="col-sm-10">
                    <input type="file" class="form-control" name="{{$param['name']}}" alt='{{$param['optional']}}' id="{{$className}}-{{$methodName}}-{{$param['name']}}"  >
                    </div>
                </div>
                @else
                <div class="form-group">
                <label for="{{$className}}-{{$methodName}}-{{$param['name']}}"
                class="col-sm-2 control-label {{$param['optional']=="2"?"text-danger":""}}">{{$param['name']}}</label>
                    <div class="col-sm-10">
                    <input type="text" class="form-control" name="{{$param['name']}}" alt='{{$param['optional']}}' id="{{$className}}-{{$methodName}}-{{$param['name']}}"  >
                    </div>
                </div>

                @endif

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
    // 初始化变量
    @php
        $paramString = "";
        $bodyString = "";
        $fileString = "";
        if(isset($method['params'])) {
            $body = [];
            $param = [];
            $file = [];
            foreach($method['params'] as $value){
                if($value['optional']=="2"){
                    $param[] = $value['name'].":''";
                } else {
                    if($value['type'] == "file") {
                        $file[] = $value['name'].":'".$className."-".$methodName."-".$value['name']."'";

                    } else {
                        $body[] = $value['name'].":''";
                    }
                }
            }
            $paramString = implode(",",$param);
            $bodyString = implode(",",$body);
            $fileString = implode(",",$file);
        }
    @endphp
    // 初始化结构体
    var {{$className}}_{{$methodName}}_data = {
        param : {{!!$paramString!!}},
        body : {{!!$bodyString!!}},
        file : {{!!$fileString!!}},
        url_string : "{{$method['http']}}",
        param_string : ""

    }
    // GET方式
    @if($method['mode'] == "GET" )
        // 绑定输入框
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
        // 测试按钮事件
        $("#{{$className}}-{{$methodName}}-test-btn").bind("click",function(){
            ajax_url = $("#{{$className}}-{{$methodName}}-test-url").text()
            $.ajax({
                type: "get",
                dataType: 'json',
                url: ajax_url,
                processData : false, // 使数据不做处理
                contentType : false, // 不要设置Content-Type请求头
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
                    layer.alert(data.responseJSON.message)
                }
            });

        });
    @endif
    // POST方式
    @if($method['mode'] == "POST" )
        // 绑定输入框
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
        // 测试按钮事件
        $("#{{$className}}-{{$methodName}}-test-btn").bind("click",function(){
            var requestString = {{$className}}_{{$methodName}}_data['body'];
            var ajax_url = $("#{{$className}}-{{$methodName}}-test-url").text();
            var requestdata = JSON.stringify(requestString);


            var formData = new FormData();
            if({{$className}}_{{$methodName}}_data['file'] != "") {
                Object.getOwnPropertyNames({{$className}}_{{$methodName}}_data['file']).forEach(function(key){
                    formData.append(key,$('#'+{{$className}}_{{$methodName}}_data['file'][key])[0].files[0]);
                })
            }
            if({{$className}}_{{$methodName}}_data['body'] != "") {
                Object.getOwnPropertyNames({{$className}}_{{$methodName}}_data['body']).forEach(function(key){
                    formData.append(key,{{$className}}_{{$methodName}}_data['body'][key]);
                })
            }

            if("{{$model}}" == "mock"){
                ajax_url += "&model=mock";
            }
            $.ajax({
                type: "post",
                dataType: 'json',
                url: ajax_url,
                data: formData,
                processData : false, // 使数据不做处理
                contentType : false, // 不要设置Content-Type请求头
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
                    layer.alert(data.responseJSON.message)
                }
            });
        })
    @endif

    // PUT方式
    @if($method['mode'] == "PUT" )
        // 绑定输入框
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
        // 测试按钮事件
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
                    } else {
                        layer.alert(data.responseJSON.message)
                    }
                }
            });
        })
    @endif

    // DELETE
    @if($method['mode'] == "DELETE")
        // 绑定输入框
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
        // 测试按钮事件
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
                    } else {
                        layer.alert(data.responseJSON.message)
                    }
                }
            });
        })
    @endif
});
</script>

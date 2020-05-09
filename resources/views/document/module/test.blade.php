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
            class="col-sm-2 control-label {{$param['describe']=="URL参数"?"text-danger":""}}">{{$param['name']}}</label>
                <div class="col-sm-10">
                <input type="text" class="form-control" name="{{$param['name']}}" alt='{{$param['describe']}}' id="{{$className}}-{{$methodName}}-{{$param['name']}}"  >
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
        var  {{$className}}_{{$methodName}}_map = {
            @if(isset($method['params']))
                @foreach($method['params'] as $param)
                {{$param['name']}}:"",
                @endforeach
                @endif
        };
        var  {{$className}}_{{$methodName}}_map_url = {
            @if(isset($method['params']))
                @foreach($method['params'] as $param)
                {{$param['name']}}:"",
                @endforeach
                @endif
        };
        @if($method['mode'] == "GET" || $method['mode'] == "DELETE")
                    //设置输入框
                    $("#{{$className}}-{{$methodName}}-test-form input").bind("keyup",function(){
                        var http_request_url = "{{$method['http']}}";
                        {{$className}}_{{$methodName}}_map[$(this).attr("name")] = $(this).val();

                        if($(this).attr("alt") == "URL参数") {
                            Object.getOwnPropertyNames({{$className}}_{{$methodName}}_map).forEach(function(key){
                                if({{$className}}_{{$methodName}}_map[key]) {
                                    http_request_url = http_request_url.replace("{"+key+"}",{{$className}}_{{$methodName}}_map[key])
                                }
                            })
                        } else {
                            var http_request_url = http_request_url+"?"+Object.keys({{$className}}_{{$methodName}}_map || {})
                                .filter((key)=>{{$className}}_{{$methodName}}_map[key])
                                .map((key)=>key + '=' + {{$className}}_{{$methodName}}_map[key])
                                .join('&')
                            if("{{$model}}" == "mock"){
                                http_request_url += "&model=mock";
                            }
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
                        var http_request_url = "{{$method['http']}}";
                        if($(this).attr("alt") == "URL参数") {
                            {{$className}}_{{$methodName}}_map_url[$(this).attr("name")] = $(this).val();
                            Object.getOwnPropertyNames({{$className}}_{{$methodName}}_map_url).forEach(function(key){
                                if({{$className}}_{{$methodName}}_map_url[key]) {
                                    http_request_url = http_request_url.replace("{"+key+"}",{{$className}}_{{$methodName}}_map_url[key])
                                }
                            })
                            if("{{$model}}" == "mock"){
                                http_request_url += "&model=mock";
                            }
                            $("#{{$className}}-{{$methodName}}-test-url").html(http_request_url);
                        } else {
                            {{$className}}_{{$methodName}}_map[$(this).attr("name")] = $(this).val();
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

<a id="{{$className}}-{{$methodName}}" style="display: block;" > </a>
<div class="content" style="margin-top: 80px">
<h2 >{{$method['title']}}</h2>
@if($method['describe'] != "")
    <p>接口说明：{{$method['describe']}}</p>
@endif
<p>访问地址： <code >{{$method['http']}}</code></p>
<p>请求方式：<code>{{$method['mode']}}</code>
<input class="btn btn-primary btn-xs show_test download_css"
       rel="{{$className}}-{{$methodName}}-test-box"
       type="button"
       value="{{$model=='mock'?"mock测试":"在线测试"}}"
       style="background-color: {{$model=='mock'?"#28a745":"#6f42c1"}};border-color:{{$model=='mock'?"#28a745":"#6f42c1"}};color: #fff;float: right">
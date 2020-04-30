@php
$defaultRespone = <<<EOF
{
    "state": 0,
    "message": "提交成功",
    "data": [] //单条数据返回{},多条数据返回[],默认为{}
}
EOF;
@endphp
<style scoped>
    .bs-example.my-bs-output::after{
        content: "响应实例："!important;
    }
</style>
    <div class="bs-example my-bs-output">
        <input class="btn btn-primary btn-xs show_output {{$className}}_{{$methodName}}_outputinfo" type="button" value="展开"
        rel="{{$className}}_{{$methodName}}_outputinfo" style="background-color: #6f42c1;color: #fff;float: right;margin-top:-30px;">
    <div style="margin:20px"></div>

    <pre id="{{$className}}_{{$methodName}}_outputinfo" style="height:120px">{{$method['output']??$defaultRespone}}</pre>
</div>
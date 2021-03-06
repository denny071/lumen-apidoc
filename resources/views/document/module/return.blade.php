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
                <th  style="width:30%">参数名</th>
                <th  style="width:70%">说明</th>
            </tr>
        </thead>
        <tbody>
            @php
            $tag = "";
            foreach($method['return'] as $return){
                if(strpos($return['describe'],"@")) {
                    list($title,$value) = explode("@",$return['describe']);
                    if ($tag != $title) {
                        echo "<tr><th colspan='2'>{$title}</th></tr>";
                        $tag = $title;
                    }
                    echo "<tr><td>&nbsp;&nbsp;&nbsp;|——{$return['name']}</td><td>{$value}</td></tr>";
                } else {
                    echo "<tr><td>{$return['name']}</td><td>{$return['describe']}</td></tr>";
                }
            }
            @endphp
        </tbody>
    </table>
</div>
@endif

@if(isset($method['return_state']))
<div class="table-responsive">
    <table class="table table-bordered table-striped table-condensed">
        <caption>响应状态</caption>
        <thead>
            <tr>
                <th  style="width:20%">HTTP状态</th>
            <th  style="width:80%">{{$method['return_state']}}</th>
            </tr>
        </thead>
    </table>
</div>
@endif


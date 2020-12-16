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
            <tr class="{{$param['optional']==0?"info":""}}">
                <td>{{$param['name']}}</td>
                <td>{{$param['type']}}</td>
                <td>{{$param['optional']==1?"是":"否"}}</td>
                <td>{{$param['describe']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
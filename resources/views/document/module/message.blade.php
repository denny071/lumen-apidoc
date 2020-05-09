
@if(isset($method['success']))
<div class="bs-callout bs-callout-success" id="callout-tables-responsive-overflow" style="margin-bottom: 10px;">
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
<div class="bs-callout bs-callout-info" id="callout-tables-responsive-overflow" style="margin-bottom: 10px;">
    <table class="table table-bordered table-striped table-condensed">
        <caption>请求响应信息</caption>
        <thead>
        <tr>
            <th style="width:20%">编码</th>
            <th style="width:80%">说明</th>
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
<div class="bs-callout bs-callout-warning" id="callout-tables-responsive-overflow" style="margin-bottom: 10px;">
    <table class="table table-bordered table-striped table-condensed">
        <caption>失败编码</caption>
        <thead>
            <tr>
                <th style="width:20%">编码</th>
                <th style="width:80%">说明</th>
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
<div class="bs-callout bs-callout-danger" id="callout-tables-responsive-overflow" style="margin-bottom: 10px;">
    <table class="table table-bordered table-striped table-condensed">
        <caption>错误编码</caption>
        <thead>
            <tr>
                <th style="width:20%">编码</th>
                <th style="width:80%">说明</th>
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

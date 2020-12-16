@if(isset($method['input']))
@if($method['mode'] == "GET")
<div class="bs-example my-bs-input">
    <code >{{$method['http'].$method['input']}}</code>
</div>
@else
<div class="bs-example my-bs-input" id="{{$className}}_{{$methodName}}_egurl">
    <code>{{!!$method['http']!!}}</code>
    <div style="margin:20px"></div>
    <?php
        $inputdata = $method['input'];
    ?>
@foreach($inputdata as $inputkey => $input)
@if(substr($input,0,13) == '"accessToken"')
    <script>
    $(function(){
        $('#{{$className}}_{{$methodName}}_egurl > code').append("?accessToken=" + '{{substr($input,17,-1)}}');
    });
    </script>
<?php
    unset($inputdata[$inputkey]);
?>
@endif
@endforeach
<pre>
{
@foreach($inputdata as $input)
    {{$input}},
@endforeach
}</pre>

    </div>
    @endif
@endif
<nav class="bs-docs-sidebar hidden-print hidden-xs hidden-sm"  style="overflow: auto; height: 700px; width: 300px" >
    <ul class="nav bs-docs-sidenav">
        @php $i= 1 @endphp
        @foreach($dataList as $className => $class)
        <li>
            <a href="#{{$className}}">{{$i}}. {{$class['title']}}</a>
            <ul class="nav">
                @php $j= 1 @endphp
                @foreach($class['method'] as $methodName => $method)
                <li><a href="#{{$className}}-{{$methodName}}">{{$i}}.{{$j++}} {{$method['title']}}</a></li>
                @endforeach
            </ul>
        </li>
        @php $i++ @endphp
        @endforeach
    </ul>
</nav>
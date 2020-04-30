
<style>
    .bs-example.my-bs-test::after{
        content: "测试API："!important;
    }
    .bs-example.my-bs-input::after{
        content: "请求实例："!important;
    }
</style>

<div class="bs-docs-section">
    <h1 class="page-header">{{$class['title']}}</h1>
    <a id="{{$className}}" style="display: block;" ></a>
    @foreach($class['method'] as $methodName => $method)

    @include("apidoc::document.module.header", ['methodName' => $methodName,'method' => $method,'model' => $model])

    @include("apidoc::document.module.test", ['methodName' => $methodName,'method' => $method,'model' => $model])

    @include("apidoc::document.module.input", ['methodName' => $methodName,'method' => $method])

    @include("apidoc::document.module.params", ['method' => $method])

    @include("apidoc::document.module.output", ['className' => $className,'methodName' => $methodName,'method' => $method])

    @include("apidoc::document.module.return", ['method' => $method])

    @include("apidoc::document.module.message", ['method' => $method])
@endforeach
</div>
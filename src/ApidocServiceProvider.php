<?php


namespace Denny071\LaravelApidoc;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class ApidocServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        Command\InstallCommand::class
    ];


     /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'apidoc');

        $this->ensureHttps();

        app('router')->group([
            'prefix' => config('apidoc.router_prefix'),
            'namespace' => "Denny071\LaravelApidoc"
        ], function ($router) {
            //API文档
            $router->get('/',  ["as" => "apiDoc","uses" => "Document@genDocument"]);
            //清除文档缓存（mock用）
            $router->get('/clear', ["as" => "apiClear", "uses" => "Document@clear"]);
             //日志浏览
            $router->get('/logs', ["as" => "logs", "uses" => 'Logs@index']);
            // 操作手册
            $router->get('/manual', ["as" => "manual", "uses" => 'Document@manual']);
             // 操作手册
             $router->get('/download', ["as" => "download", "uses" => 'DocumentHtml@download']);

        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->commands($this->commands);
    }



        /**
     * Force to set https scheme if https enabled.
     *
     * @return void
     */
    protected function ensureHttps()
    {
        if (config('admin.https') || config('admin.secure')) {
            $this->app['request']->server->set('HTTPS', true);
        }
    }


}
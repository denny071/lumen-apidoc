<?php

namespace Denny071\LaravelApidoc\Command;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'apidoc:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the apidoc resource';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $toPath = base_path("public").DIRECTORY_SEPARATOR."document";
        if (!is_dir($toPath)) {
            mkdir($toPath);
        }
        $this->copy(__DIR__."/../../resources/assets/document",$toPath);
    }



    /**
     * 目录拷贝,返回被拷贝的文件数,
     *
     * @param  mixed $source
     * @param  mixed $dest
     * @return void
     */
    protected function copy($source, $dest)
    {
        $paths = array_filter(scandir($source), function ($file) {
            return !in_array($file, ['.', '..']);
        });
        foreach ($paths as $path) {

            $sourceFullPath = $source . DIRECTORY_SEPARATOR . $path;

            $destFullPath = $dest . DIRECTORY_SEPARATOR . $path;

            if (is_dir($sourceFullPath)) {
                if (!is_dir($destFullPath)) {
                    mkdir($destFullPath);
                    chmod($destFullPath, 0755);
                }
                $this->copy($sourceFullPath, $destFullPath);
                continue;
            } else {
                copy($sourceFullPath, $destFullPath);
            }
        }
    }
}

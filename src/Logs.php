<?php

namespace Denny071\LaravelApidoc;


/**
 * Logs 日志
 */
class Logs
{
     /**
     * @var 文件
     */
    private $file;
    /**
     * @var array 日志级别对应的类型
     */
    private $levels_classes = [
        'debug' => 'info',
        'info' => 'info',
        'notice' => 'info',
        'warning' => 'warning',
        'error' => 'danger',
        'critical' => 'danger',
        'alert' => 'danger',
        'emergency' => 'danger',
        'processed' => 'info',
    ];
    /**
     * @var array 日志级别对应的图片
     */
    private $levels_imgs = [
        'debug' => 'info',
        'info' => 'info',
        'notice' => 'info',
        'warning' => 'warning',
        'error' => 'warning',
        'critical' => 'warning',
        'alert' => 'warning',
        'emergency' => 'warning',
        'processed' => 'info'
    ];

    /**
     * @var array 日志级别
     */
    private $log_levels = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
        'processed'
    ];
    /**
     * 文件最大空间
     */
    const MAX_FILE_SIZE = 52428800;

    /**
     * @var \Laravel\Lumen\Application|mixed 请求数据
     */
    protected $request;

    public function __construct()
    {
        $this->request = app('request');
    }

    public function index()
    {

        if ($this->request->input('l')) {
            $this->setFile(base64_decode($this->request->input('l')));
        }
        if ($this->request->input('dl')) {
            return $this->download($this->pathToLogFile(base64_decode($this->request->input('dl'))));
        } elseif ($this->request->has('del')) {
            app('files')->delete($this->pathToLogFile(base64_decode($this->request->input('del'))));
            return $this->redirect($this->request->url());
        } elseif ($this->request->has('delall')) {
            foreach ($this->getFiles(true) as $file) {
                app('files')->delete($this->pathToLogFile($file));
            }
            return $this->redirect($this->request->url());
        }

        return view('apidoc::logs', [
            'logs' => $this->all(),
            'files' => $this->getFiles(true),
            'current_file' => $this->getFileName()
        ]);
    }

    private function redirect($to)
    {
        if (function_exists('redirect')) {
            return redirect($to);
        }

        return app('redirect')->to($to);
    }

    private function download($data)
    {
        if (function_exists('response')) {
            return response()->download($data);
        }

        // For laravel 4.2
        return app('\Illuminate\Support\Facades\Response')->download($data);
    }

    /**
     * 设置文件
     *
     * @param string $file
     */
    public function setFile($file)
    {
        $file = $this->pathToLogFile($file);

        if (app('files')->exists($file)) {
            $this->file = $file;
        }
    }

    /**
     * @param string $file
     * @return string
     * @throws \Exception
     */
    public function pathToLogFile($file)
    {
        //获得日志路径
        $logsPath = storage_path('logs');
        //判断日志是否存在
        if (app('files')->exists($file)) { // try the absolute path
            return $file;
        }
        //日志绝对路径
        $file = $logsPath . '/' . $file;

        // check if requested file is really in the logs directory
        if (dirname($file) !== $logsPath) {
            throw new \Exception('No such log file');
        }

        return $file;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return basename($this->file);
    }

    /**
     * @return array
     */
    public function all()
    {
        $log = array();

        $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}([\+-]\d{4})?\].*/';

        if (!$this->file) {
            $log_file = $this->getFiles();
            if (!count($log_file)) {
                return [];
            }
            $this->file = $log_file[0];
        }

        if (app('files')->size($this->file) > self::MAX_FILE_SIZE) return null;

        $file = app('files')->get($this->file);

        preg_match_all($pattern, $file, $headings);

        if (!is_array($headings)) return $log;

        $log_data = preg_split($pattern, $file);

        if ($log_data[0] < 1) {
            array_shift($log_data);
        }

        foreach ($headings as $h) {
            for ($i = 0, $j = count($h); $i < $j; $i++) {
                foreach ($this->log_levels as $level) {
                    if (strpos(strtolower($h[$i]), '.' . $level) || strpos(strtolower($h[$i]), $level . ':')) {

                        preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}([\+-]\d{4})?)\](?:.*?(\w+)\.|.*?)' . $level . ': (.*?)( in .*?:[0-9]+)?$/i', $h[$i], $current);
                        if (!isset($current[4])) continue;

                        $log[] = array(
                            'context' => $current[3],
                            'level' => $level,
                            'level_class' => $this->levels_classes[$level],
                            'level_img' => $this->levels_imgs[$level],
                            'date' => $current[1],
                            'text' => $current[4],
                            'in_file' => isset($current[5]) ? $current[5] : null,
                            'stack' => preg_replace("/^\n*/", '', $log_data[$i])
                        );
                    }
                }
            }
        }

        return array_reverse($log);
    }

    /**
     * @param bool $basename
     * @return array
     */
    public function getFiles($basename = false)
    {
        $files = glob(storage_path() . '/logs/*.log');
        $files = array_reverse($files);
        $files = array_filter($files, 'is_file');
        if ($basename && is_array($files)) {
            foreach ($files as $k => $file) {
                $files[$k] = basename($file);
            }
        }
        return array_values($files);
    }
}

<?php namespace fly\flyHttp;

use fly\contracts\console\Command;
use fly\contracts\config\Config;
use fly\process\Process;
use fly\http\error\Error;
use Throwable;
use Exception;

class FlyHttp extends Command
{
    const NAME = 'FlyHttp';
    const DESCRIPTION = 'web server based on swoole.';
    protected $config;
    protected $http;
    protected $webApp;

    public function __construct(\fly\contracts\container\Container $app)
    {
        parent::__construct($app);

        $this->config = $this->app->get(Config::class)->get(self::NAME);
        $this->config['setting']['pid_file']  = $this->app->getPath('runtime').DIRECTORY_SEPARATOR.'server.pid';

        $this->setOption('-u', 'code heat update');
        $this->setOption('-d', 'daemonize');
        $this->setOption('-s', 'send signal to a master process', ['start', 'stop', 'restart', 'status']);
    }

    protected function welcome($pid=null)
    {
        $this->response->info('Server    Name: '.self::NAME);
        $this->response->info('PHP    Version: '.PHP_VERSION);
        $this->response->info('Swoole Version: '.swoole_version());
        $this->response->info('Listen    Addr: '.$this->config['host']);
        $this->response->info('Listen    Port: '.$this->config['port']);
        $this->response->info('Worker     Num: '.$this->config['setting']['worker_num']);
        if(!is_null($pid)) {
            $this->response->info('master     Pid: '.$pid);
        }
        return $this->response;
    }

    public function run()
    {
        $_u = $this->request->get('-u');
        if($_u) {
            $this->config['setting']['max_request'] = 1;
        }

        $_d = $this->request->get('-d');
        if($_d) {
            $this->config['setting']['daemonize'] = 1;
        }else{
            $this->config['setting']['daemonize'] = 0;
        }

        $_s = $this->request->get('-s');

        if(!in_array($_s, $this->options['-s']['param'])) {
           return $this->response->info($this->help());
        }

        $pid = $this->getPid();

        switch (strval($_s)) {
            case 'start':
                if(Process::isRunning($pid)) {
                    return $this->response->info(self::NAME." is running pid {$pid}");
                }else{
                    $this->http = new \swoole_http_server($this->config['host'], $this->config['port']);
                    $this->http->set($this->config['setting']);
                    $this->welcome()->send();
                    $this->start();
                }
                break;
            case 'stop':
                if(Process::isRunning($pid)) {
                    Process::kill($pid);
                    return $this->response->info(self::NAME. " stop ok");
                }else{
                    return $this->response->info(self::NAME." not running");
                }
                break;
            case 'restart':
                if(Process::isRunning($pid)) {
                    Process::reload($pid);
                    return $this->welcome($pid);
                }else{
                    return $this->response->info(self::NAME." not running");
                }
                break;
            case 'status':
                if(Process::isRunning($pid)) {
                    return $this->welcome($pid);
                }else{
                    return $this->response->info(self::NAME." not running");
                }
                break;
        }

        return $this->response->info($this->help());
    }

    protected function getPid()
    {
        $pidFile = $this->config['setting']['pid_file'];
        if (!file_exists($pidFile)) {
            return null;
        }
        $pid = file_get_contents($pidFile);
        if (Process::isRunning($pid)) {
            return $pid;
        }
        return null;
    }

    protected function start()
    {
        $this->http->on('Start', [$this, 'masterStart']);
        $this->http->on('ManagerStart', [$this, 'managerStart']);
        $this->http->on('WorkerStart', [$this, 'WorkerStart']);

        $this->http->on('request', function ($request, $response) {
            try {
                $this->webApp->set(\fly\contracts\http\Request::class, new Request($request));
                $this->webApp->set(\fly\contracts\http\Response::class, new Response($this->webApp, $response));
                $this->webApp->run();
            }catch (Exception $e) {
                $this->webApp->get(Error::class)->appException($e);
            }catch (Throwable $e) {
                $this->webApp->get(Error::class)->appException($e);
            }
            $this->webApp->del(\fly\contracts\http\Request::class);
            $this->webApp->del(\fly\contracts\http\Response::class);
            $this->webApp->del(\fly\contracts\session\Session::class);
        });

        $this->http->start();
    }

    /**
     * 主进程启动回调
     */
    public function masterStart()
    {
        swoole_set_process_name("php ".self::NAME." master {$this->config['host']}:{$this->config['port']}");
    }

    /**
     * 管理进程启动回调
     */
    public function managerStart()
    {
        swoole_set_process_name("php ".self::NAME." manager");
    }

    /**
     * 工作进程启动回调
     * @param $server
     * @param $workerId
     */
    public function workerStart($server, $workerId)
    {
        if($workerId < $server->setting['worker_num']) {
            swoole_set_process_name("php ".self::NAME." worker #{$workerId}");
            $this->webApp = new \fly\http\App(new \app\http\Kernel(), $this->app->getPath('base'));
        } else {
            swoole_set_process_name("php ".self::NAME." task #{$workerId}");
        }
    }
}

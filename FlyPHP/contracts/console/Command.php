<?php namespace fly\contracts\console;

abstract class Command
{
    /**
     * 当前命令的名称，不能与其它命令重名
     */
    const NAME = 'command name.';

    /**
     * 当前命令的描述
     */
    const DESCRIPTION = 'command description.';

    /**
     * 当前命令的参数
     * @var array
     */
    protected $options = [];

    /**
     * 依赖注入容器
     * @var \fly\contracts\container\Container
     */
    protected $app;

    /**
     * 控制台输入对象
     * @var
     */
    protected $request;

    /**
     * 控制台输出对象
     * @var
     */
    protected $response;

    public function __construct(\fly\contracts\container\Container $app)
    {
        $this->app = $app;
        $this->request = $this->app->get(Request::class);
        $this->response = $this->app->get(Response::class);
        $this->setOption('-h', 'this help.');
    }

    /**
     * 命令主体
     * @return \fly\contracts\console\Response
     */
    abstract function run();

    /**
     * 设置当前命令的参数
     * @param string $option 选项名称
     * @param string $description 选项描述
     * @param string|array $param 参数值
     */
    final protected function setOption($option, $description, $param=[])
    {
        $this->options[$option] = ['option'=>$option, 'description'=>$description, 'param'=>(array)$param];
    }

    /**
     * 返回当前命令的帮助信息
     * @return string
     */
    final public function help()
    {
        $s = static::NAME.' : '.static::DESCRIPTION;
        if(count($this->options)) {
            $s .= PHP_EOL;
            $s .= '  options:';
            $s .= PHP_EOL;
            foreach($this->options as $v) {
                $format = "    %-15s: %s";
                if(count($v['param'])) {
                    $format .= " : ";
                }
                $format .= "%s".PHP_EOL;
                $s .= sprintf($format, $v['option'], $v['description'], implode(', ', $v['param']));
            }
            rtrim($s, PHP_EOL);
        }
        return $s;
    }
}
<?php namespace fly\http\view;

/**
 * 编译引擎
 * Class CompilerEngine
 */
class CompilerEngine
{
    use CompilesEchos,
        CompilesRawPhp,
        CompilesControlStructures,
        CompilesLayouts;

    /**
     * 待执行的编译指令
     * @var array
     */
    protected $directive = ['compileEchos', 'compileStatements'];

    /**
     * 自定义指令
     * @var array
     */
    protected $customDirectives = [];

    /**
     * 编译一个模板
     * @param $content
     * @return mixed|string
     */
    public function parse($content)
    {
        $this->footer = [];

        foreach($this->directive as $v) {
            $content = call_user_func([$this, $v], $content);
        }

        if (count($this->footer) > 0) {
            $content = $this->addFooters($content);
            $this->footer = [];
        }

        return $content;
    }

    /**
     * 编译以 @ 开头的指令
     *
     * @param  string  $value
     * @return string
     */
    public function compileStatements($value)
    {
        $pattern = '/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x';
        return preg_replace_callback($pattern, function ($match) {
            if(mb_strpos($match[1], '@') !== false) {
                $match[0] = isset($match[3]) ? $match[1].$match[3] : $match[1];
            } elseif (isset($this->customDirectives[$match[1]])) {
                $match[0] = $this->callCustomDirective($match[1], $match[3]);
            } else{
                $method = 'compile'.ucfirst($match[1]);
                if(method_exists($this, $method)) {
                    $match[0] = $this->$method(isset($match[3]) ? $match[3] : null);
                }
            }
            return isset($match[3]) ? $match[0] : $match[0].$match[2];
        }, $value);
    }

    /**
     * 设置自定义编译指令
     */
    public function directive($name, callable $handler)
    {
        $this->customDirectives[$name] = $handler;
    }

    /**
     * 获取所有自定义指令
     */
    public function getCustomDirectives()
    {
        return $this->customDirectives;
    }

    /**
     * 调用自定义的指令
     */
    protected function callCustomDirective($name, $value)
    {
        $value = trim($value, "() ");
        return call_user_func($this->customDirectives[$name], $value);
    }
}
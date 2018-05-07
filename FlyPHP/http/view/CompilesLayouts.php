<?php namespace fly\http\view;

/**
 * 模板布局语法
 * Trait CompilesLayouts
 */
trait CompilesLayouts
{
    /**
     * 继承指令编译结果存储池
     * @var array
     */
    protected $footer = [];

    protected $lastSection;

    /**
     * 给内容添加页脚
     */
    protected function addFooters($result)
    {
        return ltrim($result, PHP_EOL)
            .PHP_EOL.implode(PHP_EOL, array_reverse($this->footer));
    }

    protected function compileYield($expression=null)
    {
        return "<?php echo \$__fly_layout->yieldContent{$expression}; ?>";
    }

    protected function compileSection($expression=null)
    {
        $this->lastSection = trim($expression, "()'\" ");
        return "<?php \$__fly_layout->startSection{$expression}; ?>";
    }

    protected function compileShow($expression=null)
    {
        return "<?php echo \$__fly_layout->yieldSection(); ?>";
    }

    protected function compileParent()
    {
        return ManagesLayouts::parentPlaceholder($this->lastSection ? : '');
    }

    protected function compileEndsection($expression=null)
    {
        return "<?php \$__fly_layout->endSection(); ?>";
    }

    protected function compileExtends($expression=null)
    {
        $expression = trim($expression, '()');
        $this->footer[] = "<?php echo \$__fly_layout->render({$expression}, get_defined_vars()); ?>";
        return  '';
    }
}
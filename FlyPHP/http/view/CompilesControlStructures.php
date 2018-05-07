<?php namespace fly\http\view;

/**
 * 流程控制语法
 * Trait CompilesControlStructures
 */
trait CompilesControlStructures
{
    protected function compileIf($expression=null)
    {
        return "<?php if{$expression}: ?>";
    }

    protected function compileElseif($expression=null)
    {
        return "<?php elseif{$expression}: ?>";
    }

    protected function compileElse($expression=null)
    {
        return '<?php else: ?>';
    }

    protected function compileEndif($expression=null) {
        return '<?php endif; ?>';
    }

    protected function compileSwitch($expression=null)
    {
        return "<?php switch{$expression}: ?>";
    }

    protected function compileCase($expression=null)
    {
        $expression = trim($expression, "()'\" ");
        return "<?php case {$expression}: ?>";
    }

    protected function compileBreak($expression=null)
    {
        return '<?php break; ?>';
    }

    protected function compileDefault($expression=null)
    {
        return '<?php default : ?>';
    }

    protected function compileEndswitch($expression=null)
    {
        return '<?php endswitch; ?>';
    }

    protected function compileFor($expression=null) {
        return "<?php for{$expression}: ?>";
    }

    protected function compileEndfor($expression=null){
        return '<?php endfor; ?>';
    }

    protected function compileForeach($expression=null) {
        return "<?php foreach{$expression}: ?>";
    }

    protected function compileEndforeach($expression=null) {
        return '<?php endforeach; ?>';
    }


    protected function compileWhile($expression=null) {
        return "<?php while{$expression}: ?>";
    }

    protected function compileEndwhile($expression=null){
        return '<?php endwhile; ?>';
    }

    protected function compileContinue($expression=null)
    {
        return '<?php continue; ?>';
    }

    protected function compileInclude($expression=null)
    {
        $expression = trim($expression, '()');
        return "<?php echo \$__fly_layout->render({$expression}, get_defined_vars()); ?>";
    }
}
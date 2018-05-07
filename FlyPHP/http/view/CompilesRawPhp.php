<?php namespace fly\http\view;

/**
 * php原生语法
 * Trait CompilesRawPhp
 * @package test\a
 */
trait CompilesRawPhp
{
    protected function compilePhp($expression)
    {
        return $expression ? "<?php {$expression}; ?>" : '<?php ';
    }

    protected function compileEndphp()
    {
        return ' ?>';
    }

    protected function compileUnset($expression)
    {
        return "<?php unset{$expression}; ?>";
    }

    protected function compileIsset($expression=null)
    {
        return "<?php if(isset{$expression}): ?>";
    }

    protected function compileEndIsset($expression=null)
    {
        return '<?php endif; ?>';
    }

    protected function compileEmpty($expression=null)
    {
        return "<?php if(empty{$expression}): ?>";
    }

    protected function compileEndempty($expression=null)
    {
        return '<?php endif; ?>';
    }

    protected function compileJson($expression=null)
    {
        return "<?php echo json_encode({$expression}, JSON_UNESCAPED_UNICODE); ?>";
    }
}
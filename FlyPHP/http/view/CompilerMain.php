<?php namespace fly\http\view;

use fly\contracts\http\View as InterfaceView;

/**
 * 模板编译入口
 * Class CompilerMain
 */
class CompilerMain implements InterfaceView
{
    use ManagesLayouts;

    /**
     * 模板存取操作引擎
     * @var FileEngine
     */
    protected $fileEngine;

    /**
     * 模板编译引擎
     * @var CompilerEngine
     */
    protected $compilerEngine;

    public function __construct(FileEngine $fileEngine, CompilerEngine $compilerEngine)
    {
        $this->fileEngine = $fileEngine;
        $this->compilerEngine = $compilerEngine;
    }

    /**
     * 渲染模板
     * @param $view
     * @return $this
     */
    public function render($view, $__fly_data=[], $mergeData=[])
    {
        $__fly_path = $this->make($view);
        $__fly_data = array_merge((array) $mergeData, (array) $__fly_data);
        unset($mergeData);
        ob_start();
        extract($__fly_data, EXTR_SKIP);
        unset($__fly_data);
        $__fly_layout = $this;
        include $__fly_path;
        return ltrim(ob_get_clean());
    }

    /**
     * 编译模板
     * @param $view
     * @return string 编译过的模板文件名
     */
    public function make($view)
    {
        $viewFile  = $this->fileEngine->getViewFileName($view);
        $cacheFileName = $this->fileEngine->getCacheFileName($viewFile);
        if($this->fileEngine->isExpired($viewFile, $cacheFileName)) {
            $content = $this->fileEngine->getFileContent($viewFile);
            $content = $this->compilerEngine->parse($content);
            $this->fileEngine->putFileContent($cacheFileName, $content);
            $this->fileEngine->touch($viewFile, $cacheFileName);
        }
        return $cacheFileName;
    }
}
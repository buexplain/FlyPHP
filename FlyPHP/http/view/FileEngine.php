<?php namespace fly\http\view;

use Exception;
use ErrorException;

/**
 * 模板文件读写引擎
 * Class FileEngine
 */
class FileEngine
{
    protected $viewPath;
    protected $cachePath;
    protected $suffix;

    public function __construct($viewPath, $cachePath, $suffix='.blade.php')
    {
        $this->viewPath = $viewPath;
        $this->cachePath = $cachePath;
        $this->suffix = $suffix;
    }

    public function getFileContent($file)
    {
        return file_get_contents($file);
    }

    public function putFileContent($file, $content)
    {
        return file_put_contents($file, $content, LOCK_EX);
    }

    public function getViewFileName($view)
    {
        return $this->viewPath.DIRECTORY_SEPARATOR.str_replace('.', DIRECTORY_SEPARATOR, $view).$this->suffix;
    }

    public function getCacheFileName($viewFile)
    {
        return $this->cachePath.DIRECTORY_SEPARATOR.md5($viewFile).'.php';
    }

    public function isExpired($viewFile, $cacheFileName)
    {
        try {
            return !(filemtime($viewFile) == filemtime($cacheFileName));
        }catch (ErrorException $e) {
            return true;
        }
    }

    /**
     * 修正模板与模板缓存的时间，避免开发环境与生产环境时间不一致导致，模板过期检查异常
     * @param $view
     */
    public function touch($viewFile, $cacheFileName)
    {
        touch($cacheFileName, filemtime($viewFile));
    }
}
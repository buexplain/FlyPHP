<?php namespace fly\http\view;

use Exception;

/**
 * 模板布局管理
 * Trait ManagesLayouts
 * @package test\a
 */
trait ManagesLayouts
{
    protected $sectionStack = [];

    protected static $parentPlaceholder = [];

    protected $sections = [];

    public function flush()
    {
        $this->sections = [];
        $this->sectionStack = [];
        static::$parentPlaceholder = [];
    }

    public function yieldContent($section, $default='')
    {
        $sectionContent = flyE($default);

        if (isset($this->sections[$section])) {
            $sectionContent = $this->sections[$section];
        }

        $sectionContent = str_replace('@@parent', '--parent--holder--', $sectionContent);

        return str_replace(
            '--parent--holder--', '@parent', str_replace(static::parentPlaceholder($section), '', $sectionContent)
        );
    }

    public function startSection($section, $content=null)
    {
        if ($content === null) {
            if (ob_start()) {
                $this->sectionStack[] = $section;
            }
        } else {
            $this->extendSection($section, flyE($content));
        }
    }

    protected function extendSection($section, $content)
    {
        if (isset($this->sections[$section])) {
            $content = str_replace(static::parentPlaceholder($section), $content, $this->sections[$section]);
        }
        $this->sections[$section] = $content;
    }

    public static function parentPlaceholder($section='')
    {
        if (! isset(static::$parentPlaceholder[$section])) {
            static::$parentPlaceholder[$section] = '##parent-placeholder-'.sha1($section).'##';
        }

        return static::$parentPlaceholder[$section];
    }

    public function endSection($overwrite=false)
    {
        if (empty($this->sectionStack)) {
            throw new Exception('Cannot end a section without first starting one.');
        }

        $last = array_pop($this->sectionStack);

        if ($overwrite) {
            $this->sections[$last] = ob_get_clean();
        } else {
            $this->extendSection($last, ob_get_clean());
        }

        return $last;
    }

    public function yieldSection()
    {
        if (empty($this->sectionStack)) {
            return '';
        }
        return $this->yieldContent($this->endSection());
    }
}
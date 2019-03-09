<?php
namespace Lukeed\Theme;

use Illuminate\View\FileViewFinder;

/**
 * Theme view finder
 *
 * @package Lukeed\Theme
 */
class ThemeViewFinder extends FileViewFinder
{
    /**
     * Set paths
     *
     * @param array $paths
     */
    public function setPaths(array $paths)
    {
        $this->paths = $paths;
    }

    /**
     * Prepend path to paths array
     *
     * @param $path
     */
    public function prependPath($path)
    {
        array_unshift($this->paths, $path);
    }
}

<?php
namespace Lukeed\Theme;

use Lukeed\Theme\Contracts\ThemeInfoInterface;

class ThemeInfo implements ThemeInfoInterface
{
    /**
     * Theme name
     * @var string
     */
    private $name;

    /**
     * Theme author
     * @var string
     */
    private $author;

    /**
     * Theme directory.
     * Directory must be equal to directory name containing the theme.
     * @var string
     */
    private $directory;

    /**
     * Theme version
     * @var string
     */
    private $version;

    /**
     * Theme description
     * @var string|null
     */
    private $description = null;

    /**
     * Parent theme
     * @var ThemeInfo|null
     */
    private $parent = null;

    /**
     * Theme path
     *
     * @var string
     */
    private $path;

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version = null)
    {
        $this->version = $version;
        if (!isset($version)) {
            $this->version = 'n/a';
        }
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription($description = null)
    {
        $this->description = $description;
        if (!isset($description)) {
            $this->description = 'n/a';
        }
    }

    /**
     * @return ThemeInfo|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param ThemeInfo|null $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "Theme: $this->name";
    }
}

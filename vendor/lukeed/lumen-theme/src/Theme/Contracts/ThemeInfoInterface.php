<?php
namespace Lukeed\Theme\Contracts;

interface ThemeInfoInterface
{
    public function getPath();
    public function setPath($path);

    public function getName();
    public function setName($name);

    public function getAuthor();
    public function setAuthor($author);

    public function getDirectory();
    public function setDirectory($directory);

    public function getVersion();
    public function setVersion($version = null);

    public function getDescription();
    public function setDescription($description = null);

    public function getParent();
    public function setParent($parent);
}

<?php

namespace Uwi\Calibri;

use Uwi\Filesystem\Path;

class View
{
    /**
     * Extension of view files
     * 
     * @var string
     */
    private const VIEW_EXTENSION = '.clbr.php';

    /**
     * View name
     *
     * @var string
     */
    private string $viewName;

    /**
     * Parameters shoud be injected into view
     *
     * @var array
     */
    private array $parameters;

    /**
     * Instantiate class
     *
     * @param string $viewName
     * @param array $args
     */
    public function __construct(string $viewName, array $args)
    {
        $this->viewName = $viewName;
        $this->parameters = $args;
    }

    /**
     * Get view's parameters
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Returns path to the view file
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return Path::glue(APP_BASE_PATH, 'views', $this->viewName . static::VIEW_EXTENSION);
    }



    /**
     * Convert View to a string
     *
     * @return string
     */
    public function __toString(): string
    {
        return app()->instantiate(RenderEngine::class)->render($this);
    }
}

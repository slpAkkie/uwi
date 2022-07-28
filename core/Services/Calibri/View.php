<?php

namespace Uwi\Services\Calibri;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Contracts\Http\Response\ResponsableContract;
use Uwi\Services\Calibri\Contracts\CompilerContract;

class View implements ResponsableContract
{
    /**
     * Default path to views.
     */
    protected const DEFAULT_VIEW_PATH = '/views';

    /**
     * Default view files extendion.
     */
    protected const VIEW_FILE_EXT = '.clbr.html';

    /**
     * Name of view file.
     *
     * @var string
     */
    protected string $view;

    /**
     * Path to view relate to DEFAULT_VIEW_PATH.
     *
     * @var string
     */
    protected string $viewPath;

    /**
     * Instantiare new View.
     *
     * @param string $view
     * @param array $params
     */
    public function __construct(
        protected ApplicationContract $app,
        string $view,
        protected array $params,
    ) {
        $pathToView = explode('.', $view);
        $this->view = array_pop($pathToView);
        $pathToView = implode('/', $pathToView);

        $this->viewPath = APP_BASE_PATH . self::DEFAULT_VIEW_PATH . ($pathToView ? "/$pathToView" : '');
    }

    /**
     * Convert object to response.
     *
     * @param RequestContract $request
     * @return mixed
     */
    public function toResponse(RequestContract $request): mixed
    {
        return $this->render();
    }

    /**
     * Returns view file name with extension.
     *
     * @return string
     */
    protected function getViewFileName(): string
    {
        return $this->view . self::VIEW_FILE_EXT;
    }

    /**
     * Render view.
     *
     * @return string
     */
    protected function render(): string
    {
        $compiler = $this->app->make(
            CompilerContract::class,
            sprintf('%s/%s', $this->viewPath, $this->getViewFileName()),
            $this->params
        );

        return $compiler->compile();
    }
}

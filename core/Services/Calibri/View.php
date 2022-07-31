<?php

namespace Uwi\Services\Calibri;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Http\Response\ResponsableContract;
use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\ViewContract;

class View implements ResponsableContract, ViewContract
{
    /**
     * Default view path delimiter.
     *
     * @var string
     */
    protected const VIEW_PATH_DELIMITER = '.';

    /**
     * Default path to views.
     *
     * @var string
     */
    protected const DEFAULT_VIEW_PATH = '/views';

    /**
     * Default view files extendion.
     *
     * @var string
     */
    protected const VIEW_FILE_EXT = '.clbr.html';

    /**
     * Default view content if nothing returned from Compiler::compile().
     *
     * @var string
     */
    protected const EMPTY_CONTENT_BODY = '<html></html>';

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
     * @param array<string, mixed> $params
     */
    public function __construct(
        protected ApplicationContract $app,
        string $view,
        protected array $params = [],
    ) {
        $pathToView = explode(self::VIEW_PATH_DELIMITER, $view);

        $this->view = array_pop($pathToView);
        $pathToView = implode('/', $pathToView);

        $this->viewPath = APP_BASE_PATH . self::DEFAULT_VIEW_PATH . ($pathToView ? "/$pathToView" : '');
    }

    /**
     * Convert object to response data.
     *
     * @param \Uwi\Contracts\Http\Request\RequestContract $request
     * @return mixed
     */
    public function toResponse(\Uwi\Contracts\Http\Request\RequestContract $request): mixed
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
     * Render view content.
     *
     * @return string
     */
    public function render(): string
    {
        $responseBody = $this->app->resolve(CompilerContract::class)->setView(
            sprintf('%s/%s', $this->viewPath, $this->getViewFileName()),
            $this->params
        )->compile();

        return $responseBody ? $responseBody : self::EMPTY_CONTENT_BODY;
    }
}

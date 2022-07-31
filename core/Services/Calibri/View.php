<?php

namespace Uwi\Services\Calibri;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Contracts\Http\Request\RequestContract;
use Uwi\Contracts\Http\Response\ResponsableContract;
use Uwi\Foundation\Exceptions\Exception;
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
     * Default view namespace delimiter.
     *
     * @var string
     */
    protected const VIEW_NAMESPACE_DELIMITER = '::';

    /**
     * Default path to views.
     *
     * @var string
     */
    protected const DEFAULT_VIEW_PATH = '/views';

    /**
     * Default views namespace.
     *
     * @var string
     */
    protected const DEFAULT_VIEW_NAMESPACE = 'app';

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
     * Array of available namespaces for views.
     *
     * @var array
     */
    protected static array $namespaces = [
        'app' => self::DEFAULT_VIEW_PATH,
    ];

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
        // Define namespace
        $namespace = null;
        $disassembledView = explode(self::VIEW_NAMESPACE_DELIMITER, $view, 2);
        if (count($disassembledView) !== 1) {
            $namespace = array_shift($disassembledView);
            $view = $disassembledView[0];
        }
        $namespace = $namespace ? $namespace : self::DEFAULT_VIEW_NAMESPACE;

        if (!key_exists($namespace, self::$namespaces)) {
            throw new Exception("Namespace [{$namespace}] for views is not defined");
        }
        $namespacePath = self::$namespaces[$namespace];

        // Define path
        $disassembledView = explode(self::VIEW_PATH_DELIMITER, $view);
        $this->view = array_pop($disassembledView);
        $pathToView = implode('/', $disassembledView);

        $this->viewPath = APP_BASE_PATH . $namespacePath . ($pathToView ? "/$pathToView" : '');
    }

    /**
     * Add new namespace for views.
     *
     * @param string $namespace
     * @param string $path
     * @return void
     */
    public static function namespace(string $namespace, string $path): void
    {
        self::$namespaces[$namespace] = $path;
    }

    /**
     * Convert object to response data.
     *
     * @param \Uwi\Contracts\Http\Request\RequestContract $request
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

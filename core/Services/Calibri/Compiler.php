<?php

namespace Uwi\Services\Calibri;

use Uwi\Services\Calibri\Contracts\CompilerContract;

class Compiler implements CompilerContract
{
    /**
     * View content.
     *
     * @var string|null
     */
    protected string|null $content = null;

    /**
     * Instantiate new Compiler instance.
     *
     * @param string $viewPath
     * @param array $params
     */
    public function __construct(
        protected string $viewPath,
        protected array $params = [],
    ) {
        //
    }

    /**
     * Reads view file and returns its content.
     *
     * @return string
     */
    public function read(): string
    {
        if ($this->content) {
            return $this->content;
        }

        ob_start();
        (function (string $__FILE, array $__PARAMS) {
            extract($__PARAMS);

            return include $__FILE;
        })($this->viewPath, $this->params);

        return $this->content = ob_get_clean();
    }

    /**
     * Compile provided view content.
     *
     * @return string
     */
    public function compile(): string
    {
        return $this->read();
    }
}

<?php

namespace Uwi\Services\Calibri\Directives;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;
use Uwi\Services\Calibri\Contracts\ViewContract;

class ExtendsDirective implements DirectiveContract
{
    public function __construct(
        protected ApplicationContract $app,
        protected CompilerContract $compiler,
        protected string $template,
    ) {
        $this->template = trim($this->template, '\'"');
    }

    /**
     * Returns compiled directive.
     *
     * @return string
     */
    public function compile(): string
    {
        $this->compiler->read();

        return $this->app->make(ViewContract::class, $this->template)->render();
    }
}

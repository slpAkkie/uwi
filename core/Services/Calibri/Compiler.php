<?php

namespace Uwi\Services\Calibri;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;
use Uwi\Services\Calibri\Directives\ExtendsDirective;
use Uwi\Services\Calibri\Directives\SectionDirective;
use Uwi\Services\Calibri\Directives\YieldDirective;

class Compiler implements CompilerContract
{
    /**
     * Symbol with which strarts directives in view files.
     * 
     * @var string
     */
    protected const DIRECTIVE_SYMBOL = '#';

    /**
     * Array of processors for the directives
     *
     * @var array<string, string>
     */
    protected static array $directiveHandlers = [
        'extends' => ExtendsDirective::class,
        'section' => SectionDirective::class,
        'yield' => YieldDirective::class,
    ];

    /**
     * Shared data allowed for directived.
     *
     * @var array
     */
    protected array $shared = [];

    /**
     * FileStream for view file.
     *
     * @var resource
     */
    protected $fileStream = null;

    /**
     * Instantiate new Compiler instance.
     *
     * @param ApplicationContract $app
     * @param string $viewPath
     * @param array $params
     */
    public function __construct(
        protected ApplicationContract $app,
        protected string $viewPath,
        protected array $params = [],
    ) {
        //
    }

    /**
     * Share something into Compiler.
     *
     * @param string $key
     * @param mixed $val
     * @return void
     */
    public function share(string $key, mixed $val): void
    {
        $this->shared[$key] = $val;
    }

    /**
     * Resturns shared data.
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed
    {
        return key_exists($key, $this->shared) ? $this->shared[$key] : null;
    }

    /**
     * Returns regexp to find all directives in text.
     *
     * @return string
     */
    protected function getDirectiveRegexp(): string
    {
        $directiveSymbol = self::DIRECTIVE_SYMBOL;

        return "/(?<!$directiveSymbol)$directiveSymbol([a-z]+)(\(.*\))?/";
    }

    /**
     * Returns handler for the directive or null.
     *
     * @param string $directiveName
     * @return string|null
     */
    protected function getDirectiveHandler(string $directiveName): string|null
    {
        return key_exists($directiveName, self::$directiveHandlers) ? self::$directiveHandlers[$directiveName] : null;
    }

    /**
     * Parse array of args for each directive.
     *
     * @param string $directivesArgs
     * @return array<string>
     */
    protected function parseArgs(string $directivesArgs = ''): array
    {
        $directivesArgs = explode(',', trim_once($directivesArgs, '()'));

        return array_filter(array_map(fn (string $arg) => ($arg = trim($arg)) ? $arg : null, $directivesArgs));
    }

    /**
     * Read next line of view file.
     *
     * @return string|false
     */
    protected function readNext(): string|false
    {
        if (is_null($this->fileStream)) {
            $this->fileStream = fopen($this->viewPath, 'r');
        }

        $line = fgets($this->fileStream);

        $directive = [];
        preg_match($this->getDirectiveRegexp(), $line, $directive, PREG_OFFSET_CAPTURE);
        if (count($directive)) {
            $directiveName = $directive[1][0];
            $directiveHandler = $this->getDirectiveHandler($directiveName);
            if ($directiveHandler) {
                /** @var DirectiveContract */
                $directiveHandler = $this->app->make($directiveHandler, ...$this->parseArgs($directive[2][0]));
                d($directiveHandler->compile());
            }
        }

        return $line;
    }

    /**
     * Read all view file and return compiled content.
     *
     * @return string
     */
    protected function read(): string
    {
        $content = '';

        while (($buffer = $this->readNext()) !== false) {
            $content .= $buffer;
        }

        return $content;
    }

    /**
     * Compile provided view content.
     *
     * @return string
     */
    public function compile(): string
    {
        $viewContent = $this->read();
        d($this);

        return $viewContent;
    }
}

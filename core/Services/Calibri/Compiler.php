<?php

namespace Uwi\Services\Calibri;

use Uwi\Services\Calibri\Contracts\CompilerContract;

class Compiler implements CompilerContract
{
    /**
     * Symbol with which strarts directives in view files.
     * 
     * @var string
     */
    protected const DIRECTIVE_SYMBOL = '#';

    /**
     * View content.
     *
     * @var string|null
     */
    protected string|null $content = null;

    /**
     * View directives.
     *
     * @var array<int, array<string, mixed>>
     */
    protected array $directives = [];

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
    protected function getContent(): string
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
     * Parse read content for the directives.
     *
     * @return array
     */
    protected function parseDirectives(): array
    {
        // Find all directives start with single symbol #
        // then parse directives args.
        $matches = [];
        preg_match_all($this->getDirectiveRegexp(), $this->getContent(), $matches, PREG_OFFSET_CAPTURE);
        $matches[2] = $this->parseArgs($matches[2]);

        // Collect all matches into one array with found directives.
        $directives = [];
        foreach ($matches[1] as $i => $directiveData) {
            $directives[] = [
                'name' => $directiveData[0],
                'offset' => $directiveData[1],
                'args' => $matches[2][$i],
            ];
        }

        return $this->directives = $directives;
    }

    /**
     * Parse array of args for each directive.
     *
     * @param array<string> $directivesArgs
     * @return array<array<string>>
     */
    protected function parseArgs(array $directivesArgs = []): array
    {
        return array_map(function (array $args) {
            $args = explode(',', trim_once($args[0], '()'));

            return array_filter(array_map(fn (string $arg) => ($arg = trim($arg)) ? $arg : null, $args));
        }, $directivesArgs);
    }

    /**
     * Compile provided view content.
     *
     * @return string
     */
    public function compile(): string
    {
        return $this->getContent();
    }
}

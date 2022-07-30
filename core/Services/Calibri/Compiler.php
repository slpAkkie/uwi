<?php

namespace Uwi\Services\Calibri;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;
use Uwi\Services\Calibri\Directives\ErrorDirective;
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
        'error' => ErrorDirective::class,
    ];

    /**
     * TODO: Undocumented variable
     *
     * @var string
     */
    protected string $viewPath;

    /**
     * TODO: Undocumented variable
     *
     * @var array<string, mixed>
     */
    protected array $params = [];

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
     * Remain part of last read string.
     *
     * @var string
     */
    protected string $remainLastString = '';

    /**
     * Instantiate new Compiler instance.
     *
     * @param ApplicationContract $app
     * @param string $viewPath
     * @param array $params
     */
    public function __construct(
        protected ApplicationContract $app,
    ) {
        //
    }

    /**
     * Set new view file to read from.
     *
     * @param string $viewPath
     * @param array<string, mixed> $params
     * @return static
     */
    public function setView(string $viewPath, array $params = []): static
    {
        if ($this->fileStream !== null) {
            fclose($this->fileStream);
            $this->fileStream = null;
        }

        $this->viewPath = $viewPath;
        $this->params = $params;

        return $this;
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
     * Parse provided string to existsing directives.
     *
     * @param string $line
     * @return string
     */
    protected function parseString(string $line): string
    {
        $directive = [];
        preg_match($this->getDirectiveRegexp(), $line, $directive, PREG_OFFSET_CAPTURE);
        if (count($directive)) {
            $disassembledLine = explode($directive[0][0], $line, 2);
            $line = $disassembledLine[0];
            $this->remainLastString = $disassembledLine[1];
            $directiveName = $directive[1][0];
            $directiveHandler = $this->getDirectiveHandler($directiveName);
            if ($directiveHandler) {
                /** @var DirectiveContract */
                $directiveHandler = $this->app->make($directiveHandler, ...$this->parseArgs($directive[2][0]));
                $line .= $directiveHandler->compile();
            } else {
                $line .= $directive[0][0];
            }
        }

        return $line;
    }

    /**
     * Read next until provided string isn't present.
     *
     * @param string $needle
     * @return string
     */
    public function readUntil(string $needle): string
    {
        $carry = '';
        while ($line = $this->readNext()) {
            $directive = [];
            if (preg_match("/$needle/", $line, $directive, PREG_OFFSET_CAPTURE)) {
                $disassembledLine = explode($needle, $line, 2);
                $carry .= $disassembledLine[0];
                $this->remainLastString = $disassembledLine[1];
                break;
            }

            $carry .= $line;
        }

        return trim(trim($carry, $needle));
    }

    /**
     * Read next line of view file.
     *
     * @return string|false
     */
    protected function readNext(): string|false
    {
        // TODO: Close file...
        if (is_null($this->fileStream)) {
            $this->fileStream = fopen($this->viewPath, 'r');
        }

        $line = '';

        if ($this->remainLastString !== '') {
            $line = $this->remainLastString;
            $this->remainLastString = '';
        } else {
            $line = fgets($this->fileStream);
        }

        return $line === false ? false : $this->parseString($line);
    }

    /**
     * Read all view file and return compiled content.
     *
     * @return string
     */
    public function read(): string
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

        return $viewContent;
    }
}

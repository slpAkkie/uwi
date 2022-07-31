<?php

namespace Uwi\Services\Calibri;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Foundation\Exceptions\Exception;
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
     * Array of processors for the directives.
     *
     * @var array<string, string>
     */
    protected static array $directiveHandlers = [
        'extends' => ExtendsDirective::class,
        'section' => SectionDirective::class,
        'yield' => YieldDirective::class,
    ];

    /**
     * Recursion depth of compiling.
     *
     * @var integer
     */
    protected static int $compilingDepth = 0;

    /**
     * Path to view file.
     *
     * @var string
     */
    protected string $viewPath;

    /**
     * Parameters for compiling view content.
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
    protected string $remainLastLine = '';

    /**
     * Read view content.
     *
     * @var string
     */
    protected string $content = '';

    /**
     * Instantiate Compiler.
     *
     * @param ApplicationContract $app
     */
    public function __construct(
        protected ApplicationContract $app,
    ) {
        //
    }

    /**
     * Close file stream force when Compiler is destructing.
     */
    public function __destruct()
    {
        $this->closeFileStream();
    }

    /**
     * Close file stream.
     *
     * @return void
     */
    protected function closeFileStream(): void
    {
        if ($this->fileStream !== null && is_resource($this->fileStream)) {
            fclose($this->fileStream);
            $this->fileStream = null;
        }
    }

    /**
     * Set new view file to read from.
     *
     * @param string $viewPath
     * @param array<string, mixed> $params
     * @return \Uwi\Services\Calibri\Contracts\CompilerContract
     */
    public function setView(string $viewPath, array $params = []): \Uwi\Services\Calibri\Contracts\CompilerContract
    {
        $this->closeFileStream();

        $this->content = '';
        $this->viewPath = $viewPath;
        $this->params = array_merge($this->params, $params);

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
            // Dissasemle read line into two parts.
            $disassembledLine = explode($directive[0][0], $line, 2);
            $line = $disassembledLine[0];
            // Save remain part of line.
            $this->remainLastLine = $disassembledLine[1];

            // Try to find directive jandler.
            $directiveName = $directive[1][0];
            $directiveHandlerName = $this->getDirectiveHandler($directiveName);

            if ($directiveHandlerName) {
                /** @var DirectiveContract */
                $directiveHandler = $this->app->make($directiveHandlerName, ...$this->parseArgs($directive[2][0]));
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

            // If needle in the line then disassemble the line,
            // save remain part and part of directive slot
            // then break the loop.
            if (preg_match("/$needle/", $line, $directive, PREG_OFFSET_CAPTURE)) {
                $disassembledLine = explode($needle, $line, 2);
                $carry .= $disassembledLine[0];
                $this->remainLastLine = $disassembledLine[1];

                break;
            }

            // If there is no needle in the line
            // then just append line to the carry.
            $carry .= $line;
        }

        return trim($carry);
    }

    /**
     * Read next line of view file.
     *
     * @return string|false
     */
    protected function readNext(): string|false
    {
        if (is_null($this->fileStream)) {
            $this->fileStream = @fopen($this->viewPath, 'r');

            if ($this->fileStream === false) {
                throw new Exception("No such file for view at [{$this->viewPath}]");
            }
        }

        $line = '';

        // Set lthe ine to remain of last read line
        // if it's not empty or read new line from the file.
        if ($this->remainLastLine !== '') {
            $line = $this->remainLastLine;
            $this->remainLastLine = '';
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
        while (($buffer = $this->readNext()) !== false) {
            $this->content .= $buffer;
        }

        return $this->content;
    }

    /**
     * Escape compiler directive symbol.
     *
     * @return string
     */
    protected function escapeDirectiveSymbol(): string
    {
        $this->content = preg_replace('/(' . self::DIRECTIVE_SYMBOL . '{2})/', self::DIRECTIVE_SYMBOL, $this->content);
        $this->content = preg_replace('/' . self::DIRECTIVE_SYMBOL . '{{(.*)?}}/', '{{$1}}', $this->content);

        return $this->content;
    }

    /**
     * Eval template interpolations.
     *
     * @return string
     */
    protected function evalInterpolations(): string
    {
        $directiveSymbol = self::DIRECTIVE_SYMBOL;

        // Replace all compiler interpolations with its evaluated value.
        $this->content = preg_replace_callback("/(?<!$directiveSymbol){{(.*?)}}/", function ($match) {
            extract($this->params);

            return eval("return ({$match[1]});");
        }, $this->content);

        return $this->content;
    }

    /**
     * Returns content and clear compiler cached content.
     *
     * @return string
     */
    protected function popContent(): string
    {
        $content = $this->content;
        $this->content = '';

        return $content;
    }

    /**
     * Compile provided view content.
     *
     * @return string
     */
    public function compile(): string
    {
        // Control the recursion depth.
        self::$compilingDepth++;
        $this->read();

        // Execute evalInterpolations and escapeDirectiveSymbol
        // only then it's zero recursion depth.
        try {
            if (self::$compilingDepth === 1) {
                $this->evalInterpolations();
                $this->escapeDirectiveSymbol();
            }
        } finally {
            // Control the recursion depth.
            self::$compilingDepth--;
        }

        return $this->popContent();
    }
}

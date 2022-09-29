<?php

namespace Uwi\Services\Calibri;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Foundation\Exceptions\Exception;
use Uwi\Services\Calibri\Contracts\CompilerContract;
use Uwi\Services\Calibri\Contracts\DirectiveContract;
use Uwi\Services\Calibri\Contracts\ViewContract;
use Uwi\Services\Calibri\Directives\ExtendsDirective;
use Uwi\Services\Calibri\Directives\IfDirective;
use Uwi\Services\Calibri\Directives\IncludeDirective;
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
        'include' => IncludeDirective::class,
        'if' => IfDirective::class,
    ];

    /**
     * Shared data allowed for directived.
     *
     * @var array
     */
    protected array $shared = [];

    /**
     * Stack of what should be rendered.
     *
     * @var array<array<string, mixed>>
     */
    protected array $stack = [
        // [
        //     'view' => ViewContract,
        //     'path' => string,
        //     'content' => string,
        //     'params' => array,
        //     'stream' => resource,
        //     'remainLine' => string,
        // ],
    ];

    /**
     * All content that has been read.
     *
     * @var string
     */
    protected string $content = '';

    /**
     * Current number of current stack item to compile.
     *
     * @var integer
     */
    protected int $currentStackItem = -1;

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
        $this->clearStack();
    }

    /**
     * Set new view file to read from.
     *
     * @param bool $last - Indicate that view should be compiled last of all.
     * @param \Uwi\Services\Calibri\Contracts\ViewContract $view
     * @return \Uwi\Services\Calibri\Contracts\CompilerContract
     */
    public function setView(ViewContract $view, bool $last = false): \Uwi\Services\Calibri\Contracts\CompilerContract
    {
        $prevStackItem = $this->currentStackItem >= 0 ? $this->stack[$this->currentStackItem] : null;

        $path = $view->getViewPath();
        $stream = @fopen($path, 'r');

        if ($stream === false) {
            throw new Exception("File not found for view at [$path]");
        }

        $stack = [
            'view' => $view,
            'path' => $path,
            'content' => '',
            'params' => array_merge($prevStackItem ? $prevStackItem['params'] : [], $view->getParams()),
            'stream' => $stream,
            'remainLine' => '',
        ];

        if ($last) {
            array_unshift($this->stack, $stack);
        } else {
            array_push($this->stack, $stack);
        }

        $this->currentStackItem++;

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
     * Returns array of parameters.
     *
     * @return array
     */
    public function getParams(): array
    {
        $params = [];

        for ($i = 0; $i <= $this->currentStackItem; $i++) {
            $params = array_merge($this->stack[$i]['params'], $params);
        }

        return $params;
    }

    /**
     * Close file stream.
     *
     * @return void
     */
    protected function clearStack(): void
    {
        foreach ($this->stack as $stackItem) {
            if ($stackItem['stream'] !== null && is_resource($stackItem['stream'])) {
                fclose($stackItem['stream']);
                $stackItem['stream'] = null;
            }

            $this->currentStackItem--;
        }
    }

    /**
     * Remove last item from stack.
     *
     * @return void
     */
    protected function popStack(): void
    {
        $stackItem = array_pop($this->stack);

        if ($stackItem['stream'] !== null && is_resource($stackItem['stream'])) {
            fclose($stackItem['stream']);
            $stackItem['stream'] = null;
        }

        $this->currentStackItem--;
    }

    /**
     * Returns regexp to find all directives in text.
     *
     * @return string
     */
    protected function getDirectiveRegexp(): string
    {
        $directiveSymbol = self::DIRECTIVE_SYMBOL;

        return "/(?<!$directiveSymbol)$directiveSymbol([a-zA-Z]+)(\(.*?\))/";
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
        $directivesArgs = explode(',', trimOnce($directivesArgs, '()'));

        return array_filter(array_map(fn (string $arg) => ($arg = trim($arg)) ? $arg : null, $directivesArgs));
    }

    /**
     * Parse provided string to existsing directives.
     *
     * @param string $line
     * @return string
     */
    protected function parseLine(string $line): string
    {
        $stackItem = &$this->stack[$this->currentStackItem];

        $directive = [];

        preg_match($this->getDirectiveRegexp(), $line, $directive, PREG_OFFSET_CAPTURE);
        if (count($directive)) {
            // Dissasemle read line into two parts.
            $disassembledLine = explode($directive[0][0], $line, 2);
            $line = $disassembledLine[0];
            // Save remain part of line.
            $stackItem['remainLine'] = $disassembledLine[1];

            // Try to find directive jandler.
            $directiveName = $directive[1][0];
            $directiveHandlerName = $this->getDirectiveHandler($directiveName);

            if ($directiveHandlerName) {
                /** @var DirectiveContract */
                $directiveHandler = $this->app->make($directiveHandlerName, ...$this->parseArgs($directive[2][0]));
                $line .= $directiveHandler->compile();
            } else {
                $line .= $directive[0][0] . $stackItem['remainLine'];
                $stackItem['remainLine'] = '';
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
        $directiveSymbol = self::DIRECTIVE_SYMBOL;

        $needleFound = false;
        $carry = '';

        while (($line = $this->readNext(false)) !== false) {
            // If needle in the line then disassemble the line,
            // save remain part and part of directive slot
            // then break the loop.
            if (preg_match("/(?<!$directiveSymbol)$needle/", $line, flags: PREG_OFFSET_CAPTURE)) {
                $disassembledLine = explode($needle, $line, 2);
                $carry .= $disassembledLine[0];
                $this->stack[$this->currentStackItem]['remainLine'] = $disassembledLine[1];
                $needleFound = true;

                break;
            }

            // If there is no needle in the line
            // then just append line to the carry.
            $carry .= $line;
        }

        if (!$needleFound && $this->currentStackItem > 0) {
            $this->popStack();
            $carry .= $this->readUntil($needle);
        } else if (!$needleFound) {
            throw new Exception("{$needle} expected but wasn't found");
        }

        return trim($carry);
    }

    /**
     * Read next line of view file.
     *
     * @param bool $saveLine
     * @return string|false
     */
    protected function readNext(bool $saveLine = true): string|false
    {
        $stackItem = &$this->stack[$this->currentStackItem];

        $line = '';

        // Set lthe ine to remain of last read line
        // if it's not empty or read new line from the file.
        if ($stackItem['remainLine'] !== '') {
            $line = $stackItem['remainLine'];
            $stackItem['remainLine'] = '';
        } else {
            $line = fgets($stackItem['stream']);
        }

        if ($line !== false) {
            $line = $this->parseLine($line);
            if ($saveLine) {
                $stackItem['content'] .= $line;
            }
            return $line;
        }

        return false;
    }

    /**
     * Read all view file and return compiled content.
     *
     * @return string
     */
    protected function read(): string
    {
        while ($this->currentStackItem >= 0) {
            while ($this->readNext() !== false) {
                //
            }

            if ($this->currentStackItem > 0) {
                $this->stack[$this->currentStackItem - 1]['content'] .= $this->evalInterpolations();
            } else {
                $this->content .= $this->evalInterpolations();
            }

            $this->popStack();
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
        $stackItem = &$this->stack[$this->currentStackItem];

        $directiveSymbol = self::DIRECTIVE_SYMBOL;

        // Replace all compiler interpolations with its evaluated value.
        $stackItem['content'] = preg_replace_callback("/(?<!$directiveSymbol){{(.*?)}}/", function ($match) use ($stackItem) {
            extract($stackItem['params']);

            return eval("return ({$match[1]});");
        }, $stackItem['content']);

        return $stackItem['content'];
    }

    /**
     * Returns content and clear compiler cached content.
     *
     * @return string
     */
    protected function popContent(): string
    {
        $stackItem = &$this->stack[$this->currentStackItem];

        $content = $stackItem['content'];
        $stackItem['content'] = '';

        return $content;
    }

    /**
     * Compile provided view content.
     *
     * @return string
     */
    public function compile(): string
    {
        $this->read();

        $this->escapeDirectiveSymbol();

        return $this->content;
    }
}

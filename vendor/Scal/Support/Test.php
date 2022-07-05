<?php

namespace Scal\Support;

class Test
{
    /**
     * Count of performed tests
     *
     * @var int
     */
    static $test_count = 0;

    /**
     * Name of the last class under test
     *
     * @var string
     */
    static $last_class = '';

    /**
     * Run a test to create an instance of the class
     * and display an error if it fails
     *
     * @param string $class Class name to use in test
     * @return void
     */
    public static function tryLoad(string $class): void
    {
        // Save the class name
        self::$last_class = $class;

        // Display information about the test
        echo '<pre>';
        echo '<h2 style="margin-bottom: 0em">Тест ' . ++self::$test_count . '. ' . $class . '</h2>';

        // Start the test and suppress the output for later processing
        ob_start(self::class . '::handler');
        $instance = new $class();
        echo '<p style="margin-top: 0.15em; color: green; white-space: break-spaces">';
        echo 'Test success. Loaded class: ' . ($instance->getSuccessMessage() ?? self::$last_class);
        echo '</p>';
        echo flush();

        // Finish the test
        echo '</pre>';
    }

    /**
     * Handle buffer when it flushed or cleaned
     * Display the information about the performed test
     *
     * @param string $buffer
     * @return string
     */
    private static function handler(string $buffer): string
    {
        return  !$buffer
            ? '<p style="margin-top: 0.15em; color: red; white-space: break-spaces">Test failed</p>'
            : $buffer;
    }
}

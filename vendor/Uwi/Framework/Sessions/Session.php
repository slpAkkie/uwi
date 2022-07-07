<?php

namespace Uwi\Sessions;

use Uwi\Contracts\Sessions\SessionContract;
use Uwi\Support\Arrays\ArrayWrapper;

class Session extends ArrayWrapper implements SessionContract
{
    /**
     * Calls when singleton has been instantiated and saved
     *
     * @return void
     */
    public function boot(): void
    {
        $this->start();
        $this->store = &$_SESSION;
    }

    /**
     * Start session
     *
     * @return static
     */
    public function start(): static
    {
        session_start();

        return $this;
    }

    /**
     * Destory session
     *
     * @return static
     */
    public function destory(): static
    {
        session_destroy();

        return $this;
    }

    /**
     * Destroy and start new session
     *
     * @return static
     */
    public function regenerate(): static
    {
        $this->destory();
        $this->start();

        return $this;
    }
}

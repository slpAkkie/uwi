<?php

use Framework\Foundation\Application;
use Framework\Foundation\Contracts\ApplicationContract;

function app(): ApplicationContract
{
    return Application::getInstance();
}

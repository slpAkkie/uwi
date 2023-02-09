<?php

namespace Services;

use Framework\Foundation\Contracts\ApplicationContract;
use Services\Container\Contracts\SingletonContract;
use Services\Http\Contracts\Cookies\CookieContract;
use Services\Http\Contracts\Sessions\SessionContract;
use Services\Http\Requests\Request as BaseRequest;

class Request extends BaseRequest implements SingletonContract
{
    public function __construct(ApplicationContract $app)
    {
        $this->_session = $app->get(SessionContract::class);
        $this->_cookie = $app->get(CookieContract::class);
    }
}

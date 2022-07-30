<?php

namespace Uwi\Services\Database\Lion;

use Uwi\Services\Database\Lion\Contracts\BuilderContract;
use Uwi\Services\Database\Lion\Contracts\ModelContract;
use Uwi\Services\Database\Lion\Contracts\QueryContract;
use Uwi\Services\Database\Lion\Query\Builder;
use Uwi\Services\Database\Lion\Query\Query;
use Uwi\Services\ServiceLoader;

class LionServiceLoader extends ServiceLoader
{
    /**
     * Register necessary components for Serivce.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ModelContract::class, Model::class);
        $this->app->bind(BuilderContract::class, Builder::class);
        $this->app->bind(QueryContract::class, Query::class);
    }

    /**
     * Runs when all ServiceLoader has been registered.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}

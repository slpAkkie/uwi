<?php

namespace Framework\Foundation\Contracts;

use Services\Container\Contracts\ContainerContract;

interface ApplicationContract extends ContainerContract
{
    /**
     * TODO: Undocumented function
     *
     * @param array<string> $serviceLoaders
     */
    public function __construct(array $serviceLoaders = []);

    /**
     * TODO: Undocumented function
     *
     * @param string $serviceLoaderClass
     * @return \Framework\Foundation\Contracts\ServiceLoaderContract
     */
    public function registerService(string $serviceLoaderClass): \Framework\Foundation\Contracts\ServiceLoaderContract;

    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ServiceLoaderContract $serviceLoader
     * @return void
     */
    public function bootService(ServiceLoaderContract $serviceLoader): void;

    /**
     * TODO: Undocumented function
     *
     * @return void
     */
    public function run(): void;

    /**
     * TODO: Undocumented function
     *
     * @return \Framework\Foundation\Contracts\ApplicationContract
     * @throws \Exception
     */
    public static function getInstance(): \Framework\Foundation\Contracts\ApplicationContract;
}

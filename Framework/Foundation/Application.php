<?php

namespace Framework\Foundation;

use Framework\Foundation\Contracts\ApplicationContract;
use Framework\Foundation\Contracts\KernelContract;
use Framework\Foundation\Contracts\ServiceLoaderContract;
use Framework\Foundation\Kernel\WebKernel;
use Services\Container\Container;
use Services\Container\Contracts\SingletonContract;

class Application extends Container implements ApplicationContract, SingletonContract
{
    /**
     * TODO: Undocumented constant
     *
     * @var string
     */
    protected const HELPERS_PATH = APP_ROOT_PATH . '/Framework/Helpers';

    /**
     * TODO: Undocumented variable
     *
     * @var ApplicationContract|null
     */
    protected static ?ApplicationContract $instance = null;

    /**
     * TODO: Undocumented variable
     *
     * @var array<\Framework\Foundation\Contracts\ServiceLoaderContract>
     */
    protected array $serviceLoaders = [];

    /**
     * TODO: Undocumented function
     *
     * @param array<string> $serviceLoaders
     */
    public function __construct(array $serviceLoaders = [])
    {
        $this->loadHelpers();

        $this->bind(ApplicationContract::class, static::class);
        $this->share($this, ApplicationContract::class);

        foreach ($serviceLoaders as $sl) {
            $this->serviceLoaders[] = $this->registerService($sl);
        }

        self::$instance = $this;
    }

    /**
     * TODO: Undocumented function
     *
     * @return void
     */
    protected function loadHelpers(): void
    {
        foreach (scandir(self::HELPERS_PATH) as $entry) {
            $entry = self::HELPERS_PATH . '/' . $entry;
            if (is_file($entry)) {
                include_once $entry;
            }
        }
    }

    /**
     * TODO: Undocumented function
     *
     * @param string $serviceLoaderClass
     * @return \Framework\Foundation\Contracts\ServiceLoaderContract
     */
    public function registerService(string $serviceLoaderClass): \Framework\Foundation\Contracts\ServiceLoaderContract
    {
        $serviceLoader = $this->new($serviceLoaderClass);
        $this->tap([$serviceLoader, 'register']);

        return $serviceLoader;
    }

    /**
     * TODO: Undocumented function
     *
     * @param \Framework\Foundation\Contracts\ServiceLoaderContract $serviceLoader
     * @return void
     */
    public function bootService(ServiceLoaderContract $serviceLoader): void
    {
        $this->tap([$serviceLoader, 'boot']);
    }

    /**
     * TODO: Undocumented function
     *
     * @return void
     */
    public function run(): void
    {
        foreach ($this->serviceLoaders as $sl) {
            $this->bootService($sl);
        }

        $this->bind(KernelContract::class, WebKernel::class);

        $kernel = $this->new(KernelContract::class);

        $this->tap([$kernel, 'start']);
    }

    /**
     * TODO: Undocumented function
     *
     * @return \Framework\Foundation\Contracts\ApplicationContract
     * @throws \Exception
     */
    public static function getInstance(): \Framework\Foundation\Contracts\ApplicationContract
    {
        if (is_null(self::$instance)) {
            throw new \Exception('Application was not ran');
        }

        return self::$instance;
    }
}

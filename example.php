<?php

use Dotenv\Dotenv;
use Example\Exception\ExampleException;
use Example\Handler\SentryHandler;
use Example\Services\CalculatorService;
use PrestaShop\ModuleLibServiceContainer\DependencyInjection\ServiceContainer;

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

class example extends Module
{
    /**
     * @var ServiceContainer
     */
    private $serviceContainer;

    public function __construct()
    {
        $this->name = 'example';
        $this->version = '1.0.0';
        $this->bootstrap = false;

        parent::__construct();

        $this->displayName = $this->l('PS Facebook');
        $this->description = $this->l('PS Facebook gives you all the tools you need to successfully sell and market across Facebook and Instagram. Discover new opportunities to help you scale and grow your business, and manage all your Facebook accounts and products from one place.');
        if ($this->serviceContainer === null) {
            $this->serviceContainer = new ServiceContainer($this->name, $this->getLocalPath());
        }

        $this->loadEnv();
    }

    /**
     * @param string $serviceName
     *
     * @return mixed
     */
    public function getService($serviceName)
    {
        return $this->serviceContainer->getService($serviceName);
    }

    public function getContent()
    {
        /** @var CalculatorService $calculatorService */
        $calculatorService = $this->getService(CalculatorService::class);
        $result = $calculatorService->plus('5.5', 6.4);

        try {
            throw new Exception('test exception');
        } catch (Exception $e) {
            /** @var SentryHandler $sentryHandler */
            $sentryHandler = $this->getService(SentryHandler::class);
            $sentryHandler->handle(
                new ExampleException('Failed to get content'),
                500,
                false
            );
        }
        die($result);
    }

    private function loadEnv()
    {
        if (file_exists(_PS_MODULE_DIR_ . 'example/.env')) {
            $dotenv = Dotenv::create(_PS_MODULE_DIR_ . 'ps_facebook/');
            $dotenv->load();
        }

        $dotenvDist = Dotenv::create(_PS_MODULE_DIR_ . 'example/', '.env.dist');
        $dotenvDist->load();
    }
}

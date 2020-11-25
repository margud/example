<?php

use example\services\CalculatorService;
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

        die($result);
    }
}

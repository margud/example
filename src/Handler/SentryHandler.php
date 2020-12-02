<?php

namespace Example\Handler;

use Module;
use Raven_Client;

/**
 * Handle Error.
 */
class SentryHandler
{
    /**
     * @var Raven_Client
     */
    protected $client;

    public function __construct()
    {
        $module = Module::getInstanceByName('example');

        $this->client = new Raven_Client(
            $_ENV['SENTRY_CREDENTIALS'],
            [
                'level' => 'warning',
                'tags' => [
                    'php_version' => phpversion(),
                    'module_version' => $module->version,
                    'prestashop_version' => _PS_VERSION_,
                    'module_is_enabled' => \Module::isEnabled('example'),
                    'module_is_installed' => \Module::isInstalled('example'),
                ],
            ]
        );

        $this->client->install();
    }

    /**
     * @param \Exception $error
     * @param mixed $code
     * @param bool|null $throw
     *
     * @return void
     *
     * @throws \Exception
     */
    public function handle($error, $code = null, $throw = true)
    {
        $this->client->captureException($error);
        if ($code && true === $throw) {
            http_response_code($code);
            throw $error;
        }
    }
}

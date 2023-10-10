<?php

namespace Kuttumiah\Recaptcha;

use Illuminate\Support\ServiceProvider;

/**
 * Service provider for the Recaptcha class
 *
 * @author     Md Robaiatul Islam
 * @link       https://github.com/kuttumiah
 */
class RecaptchaServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->addValidator();

        $this->loadViewsFrom(__DIR__ . '/views', 'recaptcha');
    }

    /**
     * Extends Validator to include a recaptcha type
     */
    public function addValidator()
    {
        $this->app->validator->extendImplicit('recaptcha', function ($attribute, $value, $parameters) {
            $config = $this->app['config']->get('recaptcha');

            $connection = $config['default'];
            if (1 === count($parameters)) {
                if (false === isset($config['connections'][$parameters[0]])) {
                    throw new \InvalidArgumentException('Invalid recaptcha config connection');
                }
                $connection = $parameters[0];
            }
            $captcha = app('recaptcha.service', $config['connections'][$connection]);
            $challenge = app('request')->input($captcha->getResponseKey());

            return $captcha->check($challenge, $value);
        }, 'Please ensure that you are a human!');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindRecaptcha();
        $this->handleConfig();
    }

    protected function bindRecaptcha()
    {
        $this->app->bind('recaptcha.service', function ($app, $config) {
            switch ($config['version']) {
                case 3:
                    return new Service\CheckRecaptchaV3($config);
                case 2:
                    return new Service\CheckRecaptchaV2($config);
                default:
                    return new Service\CheckRecaptcha($config);
            }
        });

        $this->app->bind('recaptcha', function () {
            $config = $this->app['config']->get('recaptcha');

            $connectionConfig = $config['connections'][$config['default']];
            return new Recaptcha($this->app->make('recaptcha.service', $connectionConfig), $connectionConfig);
        });
    }

    protected function handleConfig()
    {
        $packageConfig = __DIR__ . '/config/recaptcha.php';
        $destinationConfig = config_path('recaptcha.php');

        $this->publishes([
            $packageConfig => $destinationConfig,
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'recaptcha',
        ];
    }

}

<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('Infrastructure/config/{packages}/*.yaml');
        $container->import('Infrastructure/config/{packages}/'.$this->environment.'/*.yaml');

        if (is_file(\dirname(__DIR__).'/Infrastructure/config/services.yaml')) {
            $container->import('/Infrastructure/config/services.yaml');
            $container->import('/Infrastructure/config/{services}_'.$this->environment.'.yaml');
        } elseif (is_file($path = \dirname(__DIR__).'/Infrastructure/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('Infrastructure/config/{routes}/'.$this->environment.'/*.yaml');
        $routes->import('Infrastructure/config/{routes}/*.yaml');

        if (is_file(\dirname(__DIR__).'/src/Infrastructure/config/routes.yaml')) {
            $routes->import('Infrastructure/config/routes.yaml');
        } elseif (is_file($path = \dirname(__DIR__).'/src/Infrastructure/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }

    public function registerBundles() : iterable
    {
        $contents = require $this->getProjectDir().'/src//Infrastructure/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }
}

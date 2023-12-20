<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Ttskch\PaginatorBundle\TtskchPaginatorBundle;

/**
 * @see \Ttskch\PaginatorBundle\WebTestCase::getKernelClass()
 * @see https://zenn.dev/ttskch/articles/f9701ccf95d7c7
 * @see https://symfony.com/doc/current/configuration/micro_kernel_trait.html
 */
class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new TwigBundle(),
            new TtskchPaginatorBundle(),
        ];
    }

    private function configureContainer(ContainerConfigurator $container, LoaderInterface $loader, ContainerBuilder $builder): void
    {
        $container->import(__DIR__.'/../Resources/config/test.yaml');
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(__DIR__.'/../Resources/config/routes.yaml');
    }
}

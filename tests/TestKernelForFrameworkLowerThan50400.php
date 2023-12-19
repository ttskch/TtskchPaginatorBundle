<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * @see \Ttskch\PaginatorBundle\WebTestCase::getKernelClass()
 * @see https://zenn.dev/ttskch/articles/f9701ccf95d7c7
 * @see https://symfony.com/doc/current/configuration/micro_kernel_trait.html
 */
class TestKernelForFrameworkLowerThan50400 extends Kernel
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

    /**
     * @see https://github.com/symfony/framework-bundle/commit/461b726493b5eda87ac2be1f644b1906d2baa27f#diff-77d4032585ebfcf5a2ec3094a2272f1ab970d6013af8ec61ad85e946680f49e2L64-R50
     */
    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import(__DIR__.'/Resources/config/test.yaml');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import(__DIR__.'/Resources/config/routes.yaml');
    }
}

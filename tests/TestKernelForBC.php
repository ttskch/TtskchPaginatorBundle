<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * @see \Ttskch\PaginatorBundle\WebTestCase::getKernelClass()
 * @see https://tech.quartetcom.co.jp/2016/12/19/functional-testing-syfony-bundle/
 * @see https://symfony.com/doc/current/configuration/micro_kernel_trait.html
 */
class TestKernelForBC extends Kernel
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
     * @see https://github.com/symfony/symfony/commit/cf45eeccfc48bee212ab014f68e9807ba02501ec#diff-ee9b2c16aec8aa80f67e6b3925791d7b092fc097651bcc2df21b70e7dc8bef12L73-R58
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $loader->import(__DIR__.'/Resources/config/test.yaml');
    }

    /**
     * @see https://github.com/symfony/symfony/commit/cf45eeccfc48bee212ab014f68e9807ba02501ec#diff-ee9b2c16aec8aa80f67e6b3925791d7b092fc097651bcc2df21b70e7dc8bef12L38-R39
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        $routes->import(__DIR__.'/Resources/config/routes.yaml');
    }
}

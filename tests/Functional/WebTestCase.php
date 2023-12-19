<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Functional;

use ComposerLockParser\ComposerInfo;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * @see https://zenn.dev/ttskch/articles/f9701ccf95d7c7
 */
class WebTestCase extends BaseWebTestCase
{
    protected static function getKernelClass(): string
    {
        if (self::getFrameworkBundleVersionId() < 50100) {
            return TestKernelForFrameworkLowerThan50100::class;
        }

        if (self::getFrameworkBundleVersionId() < 50400) {
            return TestKernelForFrameworkLowerThan50400::class;
        }

        return TestKernel::class;
    }

    private static function getFrameworkBundleVersionId(): int
    {
        $composerInfo = new ComposerInfo(__DIR__.'/../../composer.lock');
        $package = $composerInfo->getPackages()->getByName('symfony/framework-bundle');
        $version = ltrim($package->getVersion(), 'v');

        if (false === preg_match('/^\d+\.\d+\.\d+$/', $version)) {
            throw new \RuntimeException('Only can test with stable version of "symfony/framework-bundle".');
        }

        [$major, $minor, $patch] = explode('.', $version);

        return intval(sprintf('%d%02d%02d', $major, $minor, $patch));
    }
}

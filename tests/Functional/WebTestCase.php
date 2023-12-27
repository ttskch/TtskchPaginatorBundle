<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Functional;

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
        /** @var array{packages-dev: array<array{name: string, version: string}>} $lock */
        $lock = json_decode(strval(file_get_contents(__DIR__.'/../../composer.lock')), true, flags: JSON_THROW_ON_ERROR);
        foreach ($lock['packages-dev'] as $package) {
            if ('symfony/framework-bundle' === $package['name']) {
                $version = ltrim($package['version'], 'v');

                if (false === preg_match('/^\d+\.\d+\.\d+$/', $version)) {
                    throw new \RuntimeException('Only can test with stable version of "symfony/framework-bundle".');
                }

                [$major, $minor, $patch] = explode('.', $version);

                return intval(sprintf('%d%02d%02d', $major, $minor, $patch));
            }
        }

        throw new \RuntimeException('"symfony/framework-bundle" is not installed.');
    }
}

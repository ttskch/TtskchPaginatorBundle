<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Functional\Twig;

use Ttskch\PaginatorBundle\Context;
use Ttskch\PaginatorBundle\Criteria\Criteria;
use Ttskch\PaginatorBundle\Tests\Functional\WebTestCase;
use Ttskch\PaginatorBundle\Twig\TtskchPaginatorExtension;
use Twig\Environment;

class TtskchPaginatorExtensionTest extends WebTestCase
{
    public function testRenderPager(): void
    {
        $client = self::createClient();
        $client->request('GET', '/foo/bar/?key1=value1&key2=value2');
        $container = $client->getContainer();
        $container->get('request_stack')->push($client->getRequest());

        $context = $container->get(Context::class.'.pub');
        assert($context instanceof Context);

        $twig = $container->get('twig.pub');
        assert($twig instanceof Environment);

        $context->initialize(
            fn () => [],
            fn () => 5,
            new Criteria(''),
        );

        $SUT = new TtskchPaginatorExtension($context, $twig);

        $text = $SUT->renderPager();

        $expected = <<<EOT
route
route_param1=foo,route_param2=bar
key1=value1,key2=value2
limit
2
page
1
1
2
1
3
1
2
1
5

EOT;

        self::assertEquals($expected, $text);
    }

    public function testRenderSortableLink(): void
    {
        $client = self::createClient();
        $client->request('GET', '/foo/bar/?key1=value1&key2=value2');
        $container = $client->getContainer();
        $container->get('request_stack')->push($client->getRequest());

        /** @var Context $context */
        $context = $container->get(Context::class.'.pub');

        /** @var Environment $twig */
        $twig = $container->get('twig.pub');

        $context->initialize(
            fn () => [],
            fn () => 0,
            new Criteria('name'),
        );

        $SUT = new TtskchPaginatorExtension($context, $twig);

        $text = $SUT->renderSortableLink('name');

        $expected = <<<EOT
route
route_param1=foo,route_param2=bar
key1=value1,key2=value2,page=1
sort
name
direction
asc
desc
Name

EOT;
        self::assertEquals($expected, $text);

        $text = $SUT->renderSortableLink('name', '<small>Name</small>', true);

        $expected = <<<EOT
route
route_param1=foo,route_param2=bar
key1=value1,key2=value2,page=1
sort
name
direction
asc
desc
<small>Name</small>

EOT;
        self::assertEquals($expected, $text);
    }
}

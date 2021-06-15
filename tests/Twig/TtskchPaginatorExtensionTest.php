<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Twig;

use Ttskch\PaginatorBundle\Context;
use Ttskch\PaginatorBundle\WebTestCase;
use Twig\Environment;

class TtskchPaginatorExtensionTest extends WebTestCase
{
    public function testRenderPager(): void
    {
        $client = self::createClient();
        $client->request('GET', '/foo/bar/?key1=value1&key2=value2');
        $container = $client->getContainer();
        $container->get('request_stack')->push($client->getRequest());

        /** @var Context $context */
        $context = $container->get('ttskch_paginator.context');

        /** @var Environment $twig */
        $twig = $container->get('twig.alias');

        $context->initialize(
            '',
            null,
            function () {
                return 5;
            }
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

        $this->assertEquals($expected, $text);
    }

    public function testRenderSortableLink(): void
    {
        $client = self::createClient();
        $client->request('GET', '/foo/bar/?key1=value1&key2=value2');
        $container = $client->getContainer();
        $container->get('request_stack')->push($client->getRequest());

        /** @var Context $context */
        $context = $container->get('ttskch_paginator.context');

        /** @var Environment $twig */
        $twig = $container->get('twig.alias');

        $context->initialize('name');

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
        $this->assertEquals($expected, $text);

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
        $this->assertEquals($expected, $text);
    }
}

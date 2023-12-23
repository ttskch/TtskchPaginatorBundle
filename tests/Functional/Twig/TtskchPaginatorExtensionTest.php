<?php

declare(strict_types=1);

namespace Functional\Twig;

use Ttskch\PaginatorBundle\Tests\Functional\WebTestCase;

class TtskchPaginatorExtensionTest extends WebTestCase
{
    /**
     * @see TestController::pager()
     */
    public function testRenderPager(): void
    {
        $client = self::createClient();
        $client->request('GET', '/test/pager/foo/bar?key1=value1&key2=value2');

        $actual = trim(strval($client->getResponse()->getContent()));

        $expected = <<<EOT
test_pager
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
        self::assertEquals($expected, $actual);
    }

    /**
     * @see TestController::sortable()
     */
    public function testRenderSortableLink(): void
    {
        $client = self::createClient();
        $client->request('GET', '/test/sortable/foo/bar?key1=value1&key2=value2');

        $actual = trim(strval($client->getResponse()->getContent()));

        $expected = <<<EOT
test_sortable
route_param1=foo,route_param2=bar
key1=value1,key2=value2,page=1
sort
name
direction
asc
desc
Name
EOT;
        self::assertEquals($expected, $actual);
    }
}

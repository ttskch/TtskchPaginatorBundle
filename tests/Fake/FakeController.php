<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Fake;

use Symfony\Component\HttpFoundation\Response;

class FakeController
{
    public function index()
    {
        return new Response();
    }
}

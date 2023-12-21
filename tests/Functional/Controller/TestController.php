<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Ttskch\PaginatorBundle\Counter\CallbackCounter;
use Ttskch\PaginatorBundle\Criteria\Criteria;
use Ttskch\PaginatorBundle\Paginator;
use Ttskch\PaginatorBundle\Slicer\CallbackSlicer;

class TestController extends AbstractController
{
    /**
     * @param Paginator<array{}, Criteria> $paginator
     */
    public function pager(Paginator $paginator): Response
    {
        $paginator->initialize(
            new CallbackSlicer(fn () => []),
            new CallbackCounter(fn () => 5),
            new Criteria(''),
        );

        return $this->render('test/pager.html.twig');
    }

    /**
     * @param Paginator<array{}, Criteria> $paginator
     */
    public function sortable(Paginator $paginator): Response
    {
        $paginator->initialize(
            new CallbackSlicer(fn () => []),
            new CallbackCounter(fn () => 0),
            new Criteria('name'),
        );

        return $this->render('test/sortable.html.twig');
    }
}

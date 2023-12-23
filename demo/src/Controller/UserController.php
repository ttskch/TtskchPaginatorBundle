<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ttskch\PaginatorBundle\Counter\Doctrine\ORM\QueryBuilderCounter;
use Ttskch\PaginatorBundle\Criteria\Criteria;
use Ttskch\PaginatorBundle\Paginator;
use Ttskch\PaginatorBundle\Slicer\Doctrine\ORM\QueryBuilderSlicer;

#[Route('/user', name: 'user_')]
class UserController extends AbstractController
{
    /**
     * @param Paginator<\Traversable<array-key, User>, Criteria> $paginator
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(UserRepository $repository, Paginator $paginator): Response
    {
        $qb = $repository->createQueryBuilder('u');
        $paginator->initialize(new QueryBuilderSlicer($qb), new QueryBuilderCounter($qb), new Criteria('id'));

        return $this->render('user/index.html.twig', [
            'users' => $paginator->getSlice(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}

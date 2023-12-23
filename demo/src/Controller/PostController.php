<?php

declare(strict_types=1);

namespace App\Controller;

use App\Criteria\PostCriteria;
use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ttskch\PaginatorBundle\Counter\CallbackCounter;
use Ttskch\PaginatorBundle\Paginator;
use Ttskch\PaginatorBundle\Slicer\CallbackSlicer;

#[Route('/post', name: 'post_')]
class PostController extends AbstractController
{
    /**
     * @param Paginator<\Traversable<array-key, Post>, PostCriteria> $paginator
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(PostRepository $repository, Paginator $paginator): Response
    {
        $paginator->initialize(
            new CallbackSlicer($repository->sliceByCriteria(...)),
            new CallbackCounter($repository->countByCriteria(...)),
            new PostCriteria('id'),
        );

        return $this->render('post/index.html.twig', [
            'posts' => $paginator->getSlice(),
            'form' => $paginator->getForm()->createView(),
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}

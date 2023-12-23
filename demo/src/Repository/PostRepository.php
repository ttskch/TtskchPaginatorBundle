<?php

declare(strict_types=1);

namespace App\Repository;

use App\Criteria\PostCriteria;
use App\Entity\Post;
use Cake\Chronos\Chronos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Ttskch\PaginatorBundle\Counter\Doctrine\ORM\QueryBuilderCounter;
use Ttskch\PaginatorBundle\Slicer\Doctrine\ORM\QueryBuilderSlicer;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return \Traversable<array-key, Post>
     */
    public function sliceByCriteria(PostCriteria $criteria): \Traversable
    {
        $qb = $this->createQueryBuilderFromCriteria($criteria);
        $slicer = new QueryBuilderSlicer($qb);

        return $slicer->slice($criteria);
    }

    public function countByCriteria(PostCriteria $criteria): int
    {
        $qb = $this->createQueryBuilderFromCriteria($criteria);
        $counter = new QueryBuilderCounter($qb);

        return $counter->count($criteria);
    }

    private function createQueryBuilderFromCriteria(PostCriteria $criteria): QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');

        if (null !== $criteria->query) {
            $qb
                ->orWhere('p.subject like :query')
                ->orWhere('p.body like :query')
                ->setParameter('query', '%'.str_replace('%', '\%', $criteria->query).'%')
            ;
        }

        if (null !== $criteria->after) {
            $qb
                ->andWhere('p.date >= :after')
                ->setParameter('after', $criteria->after, Types::DATETIME_MUTABLE)
            ;
        }

        if (null !== $criteria->before) {
            $qb
                ->andWhere('p.date <= :before')
                ->setParameter('before', (new Chronos($criteria->before))->endOfDay(), Types::DATETIME_MUTABLE)
            ;
        }

        return $qb;
    }
}

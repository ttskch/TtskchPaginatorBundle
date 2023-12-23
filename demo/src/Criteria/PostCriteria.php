<?php

declare(strict_types=1);

namespace App\Criteria;

use App\Criteria\Form\PostSearchType;
use Ttskch\PaginatorBundle\Criteria\AbstractCriteria;

class PostCriteria extends AbstractCriteria
{
    public ?string $query = null;
    public ?\DateTimeInterface $after = null;
    public ?\DateTimeInterface $before = null;

    public function __construct(string $sort)
    {
        parent::__construct($sort);
    }

    public function getFormTypeClass(): string
    {
        return PostSearchType::class;
    }
}

<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Twig;

use Ttskch\PaginatorBundle\Context;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TtskchPaginatorExtension extends AbstractExtension
{
    /**
     * @var Context
     */
    private $context;

    /**
     * @var Environment
     */
    private $twig;

    public function __construct(Context $context, Environment $twig)
    {
        $this->context = $context;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('ttskch_paginator_pager', [$this, 'renderPager'], ['is_safe' => ['html']]),
            new TwigFunction('ttskch_paginator_sortable', [$this, 'renderSortableLink'], ['is_safe' => ['html']]),
        ];
    }

    public function renderPager(string $templateName = null, array $context = []): string
    {
        $templateName = $templateName ?: $this->context->config->templatePager;

        $currentPage = $this->context->criteria->page;
        $firstPage = 1;
        $lastPage = max(1, intval(ceil($this->context->count / $this->context->criteria->limit)));
        $leftPage = max($currentPage - (intval(floor(($this->context->config->pageRange - 1) / 2))), $firstPage);
        $rightPage = min($leftPage + $this->context->config->pageRange - 1, $lastPage);
        if ($rightPage === $lastPage) {
            $leftPage = max($rightPage - $this->context->config->pageRange + 1, $firstPage);
        }

        $rightItem = min($this->context->count, $this->context->criteria->limit * $this->context->criteria->page);
        $leftItem = min($rightItem, $this->context->criteria->limit * ($this->context->criteria->page - 1) + 1);

        $context = array_merge($context, [
            'route' => $this->context->request ? $this->context->request->get('_route') : null,
            'queries' => $this->context->request ? $this->context->request->query->all() : [],
            'limit_name' => $this->context->config->limitName,
            'limit_current' => $this->context->criteria->limit,
            'page_name' => $this->context->config->pageName,
            'page_current' => $currentPage,
            'page_left' => $leftPage,
            'page_right' => $rightPage,
            'page_first' => $firstPage,
            'page_last' => $lastPage,
            'item_left' => $leftItem,
            'item_right' => $rightItem,
            'item_first' => 1,
            'item_last' => $this->context->count,
        ]);

        return $this->twig->render($templateName, $context);
    }

    public function renderSortableLink(string $key, string $text = null, string $templateName = null, array $context = []): string
    {
        $templateName = $templateName ?: $this->context->config->templateSortable;

        $isSorted = $key === $this->context->criteria->sort;

        $currentDirection = $isSorted ? $this->context->criteria->direction : null;
        $nextDirection = $isSorted ? (strtolower($currentDirection) === 'asc' ? 'desc' : 'asc') : $this->context->config->sortDirectionDefault;

        // reset page number after re-sorting.
        $queries = $this->context->request ? $this->context->request->query->all() : [];
        $queries[$this->context->config->pageName] = 1;

        $context = array_merge($context, [
            'route' => $this->context->request ? $this->context->request->get('_route') : null,
            'queries' => $queries,
            'key_name' => $this->context->config->sortKeyName,
            'key' => $key,
            'direction_name' => $this->context->config->sortDirectionName,
            'direction_current' => $currentDirection,
            'direction_next' => $nextDirection,
            'text' => $text ?: ucwords($key),
        ]);

        return $this->twig->render($templateName, $context);
    }

    public function getName(): string
    {
        return 'ttskch_paginator';
    }
}

<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Twig;

use Ttskch\PaginatorBundle\Config;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;
use Ttskch\PaginatorBundle\Paginator;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class TtskchPaginatorRuntime implements RuntimeExtensionInterface
{
    /**
     * @param Paginator<covariant mixed, covariant CriteriaInterface> $paginator
     */
    public function __construct(
        /** @readonly */
        private Config $config,
        /** @readonly */
        private Paginator $paginator,
    ) {
    }

    /**
     * @param array<string, mixed> $context
     */
    public function renderPager(Environment $twig, string $templateName = null, array $context = []): string
    {
        $templateName ??= $this->config->templatePager;

        $currentPage = $this->paginator->getCriteria()->getPage();
        $firstPage = 1;
        $lastPage = max(1, intval(ceil($this->paginator->getCount() / $this->paginator->getCriteria()->getLimit())));
        $leftPage = max($currentPage - intval(floor(($this->config->pageRange - 1) / 2)), $firstPage);
        $rightPage = min($leftPage + $this->config->pageRange - 1, $lastPage);
        if ($rightPage === $lastPage) {
            $leftPage = max($rightPage - $this->config->pageRange + 1, $firstPage);
        }

        $rightItem = min($this->paginator->getCount(), $this->paginator->getCriteria()->getLimit() * $this->paginator->getCriteria()->getPage());
        $leftItem = min($rightItem, $this->paginator->getCriteria()->getLimit() * ($this->paginator->getCriteria()->getPage() - 1) + 1);

        $context = array_merge($context, [
            'route' => $this->paginator->getRequest()?->get('_route'),
            'route_params' => $this->paginator->getRequest()?->get('_route_params'),
            'queries' => $this->paginator->getRequest()?->query->all() ?? [],
            'limit_name' => $this->config->limitName,
            'limit_current' => $this->paginator->getCriteria()->getLimit(),
            'page_name' => $this->config->pageName,
            'page_current' => $currentPage,
            'page_left' => $leftPage,
            'page_right' => $rightPage,
            'page_first' => $firstPage,
            'page_last' => $lastPage,
            'item_left' => $leftItem,
            'item_right' => $rightItem,
            'item_first' => 1,
            'item_last' => $this->paginator->getCount(),
        ]);

        return $twig->render($templateName, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function renderSortableLink(Environment $twig, string $key, string $label = null, bool $labelHtml = false, string $templateName = null, array $context = []): string
    {
        $templateName ??= $this->config->templateSortable;

        $isSorted = $key === $this->paginator->getCriteria()->getSort();

        $currentDirection = $isSorted ? $this->paginator->getCriteria()->getDirection() : '';
        $counterDirection = CriteriaInterface::ASC === $currentDirection ? CriteriaInterface::DESC : CriteriaInterface::ASC;
        $nextDirection = $isSorted ? $counterDirection : $this->config->sortDirectionDefault;

        // Reset page number after re-sorting.
        $queries = $this->paginator->getRequest()?->query->all() ?? [];
        $queries[$this->config->pageName] = 1;

        $context = array_merge($context, [
            'route' => $this->paginator->getRequest()?->get('_route'),
            'route_params' => $this->paginator->getRequest()?->get('_route_params'),
            'queries' => $queries,
            'key_name' => $this->config->sortKeyName,
            'key' => $key,
            'direction_name' => $this->config->sortDirectionName,
            'direction_current' => $currentDirection,
            'direction_next' => $nextDirection,
            'label' => $label ?? ucwords($key),
            'label_html' => $labelHtml,
        ]);

        return $twig->render($templateName, $context);
    }

    public function getName(): string
    {
        return 'ttskch_paginator';
    }
}

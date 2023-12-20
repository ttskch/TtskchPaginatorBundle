<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Twig;

use Ttskch\PaginatorBundle\Context;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * @internal
 */
class TtskchPaginatorExtension extends AbstractExtension
{
    public function __construct(
        private Context $context,
        private Environment $twig,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('ttskch_paginator_pager', [$this, 'renderPager'], ['is_safe' => ['html']]),
            new TwigFunction('ttskch_paginator_sortable', [$this, 'renderSortableLink'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param array<string, mixed> $context
     */
    public function renderPager(string $templateName = null, array $context = []): string
    {
        $templateName ??= $this->context->getConfig()->templatePager;

        $currentPage = $this->context->getCriteria()->getPage();
        $firstPage = 1;
        $lastPage = max(1, intval(ceil($this->context->getCount() / $this->context->getCriteria()->getLimit())));
        $leftPage = max($currentPage - intval(floor(($this->context->getConfig()->pageRange - 1) / 2)), $firstPage);
        $rightPage = min($leftPage + $this->context->getConfig()->pageRange - 1, $lastPage);
        if ($rightPage === $lastPage) {
            $leftPage = max($rightPage - $this->context->getConfig()->pageRange + 1, $firstPage);
        }

        $rightItem = min($this->context->getCount(), $this->context->getCriteria()->getLimit() * $this->context->getCriteria()->getPage());
        $leftItem = min($rightItem, $this->context->getCriteria()->getLimit() * ($this->context->getCriteria()->getPage() - 1) + 1);

        $context = array_merge($context, [
            'route' => $this->context->getRequest()?->get('_route'),
            'route_params' => $this->context->getRequest()?->get('_route_params'),
            'queries' => $this->context->getRequest()?->query->all() ?? [],
            'limit_name' => $this->context->getConfig()->limitName,
            'limit_current' => $this->context->getCriteria()->getLimit(),
            'page_name' => $this->context->getConfig()->pageName,
            'page_current' => $currentPage,
            'page_left' => $leftPage,
            'page_right' => $rightPage,
            'page_first' => $firstPage,
            'page_last' => $lastPage,
            'item_left' => $leftItem,
            'item_right' => $rightItem,
            'item_first' => 1,
            'item_last' => $this->context->getCount(),
        ]);

        return $this->twig->render($templateName, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function renderSortableLink(string $key, string $label = null, bool $labelHtml = false, string $templateName = null, array $context = []): string
    {
        $templateName ??= $this->context->getConfig()->templateSortable;

        $isSorted = $key === $this->context->getCriteria()->getSort();

        $currentDirection = $isSorted ? $this->context->getCriteria()->getDirection() : '';
        $counterDirection = CriteriaInterface::ASC === $currentDirection ? CriteriaInterface::DESC : CriteriaInterface::ASC;
        $nextDirection = $isSorted ? $counterDirection : $this->context->getConfig()->sortDirectionDefault;

        // Reset page number after re-sorting.
        $queries = $this->context->getRequest()?->query->all() ?? [];
        $queries[$this->context->getConfig()->pageName] = 1;

        $context = array_merge($context, [
            'route' => $this->context->getRequest()?->get('_route'),
            'route_params' => $this->context->getRequest()?->get('_route_params'),
            'queries' => $queries,
            'key_name' => $this->context->getConfig()->sortKeyName,
            'key' => $key,
            'direction_name' => $this->context->getConfig()->sortDirectionName,
            'direction_current' => $currentDirection,
            'direction_next' => $nextDirection,
            'label' => $label ?? ucwords($key),
            'label_html' => $labelHtml,
        ]);

        return $this->twig->render($templateName, $context);
    }

    public function getName(): string
    {
        return 'ttskch_paginator';
    }
}

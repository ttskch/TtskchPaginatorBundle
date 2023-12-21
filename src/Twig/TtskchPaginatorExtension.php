<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class TtskchPaginatorExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        $options = ['is_safe' => ['html'], 'needs_environment' => true];

        return [
            new TwigFunction('ttskch_paginator_pager', [TtskchPaginatorRuntime::class, 'renderPager'], $options),
            new TwigFunction('ttskch_paginator_sortable', [TtskchPaginatorRuntime::class, 'renderSortableLink'], $options),
        ];
    }
}

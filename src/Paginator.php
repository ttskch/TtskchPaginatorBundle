<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Ttskch\PaginatorBundle\Counter\CounterInterface;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;
use Ttskch\PaginatorBundle\Exception\UninitializedCriteriaException;
use Ttskch\PaginatorBundle\Exception\UninitializedPaginatorException;
use Ttskch\PaginatorBundle\Slicer\SlicerInterface;

/**
 * @template TSlice
 * @template TCriteria of CriteriaInterface
 */
class Paginator
{
    /** @var TSlice|null */
    private mixed $slice = null;
    private ?int $count = null;
    /** @var TCriteria|null */
    private ?CriteriaInterface $criteria = null;
    private ?FormInterface $form = null;

    public function __construct(
        /** @readonly */
        private Config $config,
        /** @readonly */
        private FormFactoryInterface $formFactory,
        /** @readonly */
        private RequestStack $requestStack,
    ) {
    }

    /**
     * @param SlicerInterface<TSlice> $slicer
     * @param TCriteria               $criteria
     * @param array<string, mixed>    $formOptions
     */
    public function initialize(
        SlicerInterface $slicer,
        CounterInterface $counter,
        CriteriaInterface $criteria,
        array $formOptions = [],
        bool $handleRequest = true,
    ): void {
        $this->criteria = $criteria;

        try {
            $criteria->getPage();
        } catch (UninitializedCriteriaException) {
            $criteria->setPage(1);
        }

        try {
            $criteria->getLimit();
        } catch (UninitializedCriteriaException) {
            $criteria->setLimit($this->config->limitDefault);
        }

        try {
            $criteria->getDirection();
        } catch (UninitializedCriteriaException) {
            $criteria->setDirection($this->config->sortDirectionDefault);
        }

        $this->form = $this->formFactory->createNamed('', $this->criteria->getFormTypeClass(), $criteria, array_merge([
            'method' => 'GET',
        ], $formOptions));

        if ($handleRequest) {
            $this->handleRequest();
        }

        $this->slice = $slicer->slice($criteria);
        $this->count = $counter->count($criteria);
    }

    public function handleRequest(): void
    {
        // Map configured key names to actual key names.
        $map = [
            'page' => $this->config->pageName,
            'limit' => $this->config->limitName,
            'sort' => $this->config->sortKeyName,
            'direction' => $this->config->sortDirectionName,
        ];
        $submittedData = $this->requestStack->getCurrentRequest()?->query->all();
        foreach ($map as $actualKey => $configuredKey) {
            if ($actualKey !== $configuredKey && isset($submittedData[$configuredKey])) {
                $submittedData[$actualKey] = $submittedData[$configuredKey];
                unset($submittedData[$configuredKey]);
            }
        }

        // Don't use Form::handleRequest() because it will clear properties corresponding empty queries.
        $this->getForm()->submit($submittedData, false);
    }

    /**
     * @return TSlice|null
     */
    public function getSlice(): mixed
    {
        return $this->slice;
    }

    public function getCount(): int
    {
        if (null === $this->count) {
            throw new UninitializedPaginatorException();
        }

        return $this->count;
    }

    /**
     * @return TCriteria
     */
    public function getCriteria(): CriteriaInterface
    {
        if (null === $this->criteria) {
            throw new UninitializedPaginatorException();
        }

        return $this->criteria;
    }

    public function getForm(): FormInterface
    {
        if (null === $this->form) {
            throw new UninitializedPaginatorException();
        }

        return $this->form;
    }

    public function getRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
    }
}

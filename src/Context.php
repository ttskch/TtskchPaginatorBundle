<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Ttskch\PaginatorBundle\Criteria\CriteriaInterface;
use Ttskch\PaginatorBundle\Exception\UnexpectedCountTypeException;
use Ttskch\PaginatorBundle\Exception\UninitializedContextException;

class Context
{
    private mixed $slice = null;
    private ?int $count = null;
    private ?CriteriaInterface $criteria = null;
    private ?FormInterface $form = null;
    private ?Request $request;

    public function __construct(
        private Config $config,
        private FormFactoryInterface $formFactory,
        RequestStack $requestStack,
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param callable(CriteriaInterface): mixed $slicer
     * @param callable(CriteriaInterface): int   $counter
     * @param array<string, mixed>               $formOptions
     */
    public function initialize(
        callable $slicer,
        callable $counter,
        CriteriaInterface $criteria,
        array $formOptions = [],
        bool $handleRequest = true,
    ): void {
        $this->criteria = $criteria;

        $criteria->setPage(1);
        $criteria->setLimit($this->config->limitDefault);
        $criteria->setDirection($this->config->sortDirectionDefault);

        $this->form = $this->formFactory->createNamed('', $this->criteria->getFormTypeClass(), $criteria, array_merge([
            'method' => 'GET',
        ], $formOptions));

        if ($handleRequest) {
            $this->handleRequest();
        }

        $this->slice = $slicer($criteria);

        $count = $counter($criteria);

        if (!is_int($count)) {
            throw new UnexpectedCountTypeException();
        }

        $this->count = $count;
    }

    public function handleRequest(): void
    {
        if (null === $this->form) {
            throw new UninitializedContextException();
        }

        // Don't use Form::handleRequest() because it will clear properties corresponding empty queries.
        $this->form->submit($this->request?->query->all(), false);
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function getSlice(): mixed
    {
        return $this->slice;
    }

    public function getCount(): int
    {
        if (null === $this->count) {
            throw new UninitializedContextException();
        }

        return $this->count;
    }

    public function getCriteria(): CriteriaInterface
    {
        if (null === $this->criteria) {
            throw new UninitializedContextException();
        }

        return $this->criteria;
    }

    public function getForm(): FormInterface
    {
        if (null === $this->form) {
            throw new UninitializedContextException();
        }

        return $this->form;
    }

    public function getRequest(): ?Request
    {
        return $this->request;
    }
}

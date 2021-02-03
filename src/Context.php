<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Ttskch\PaginatorBundle\Entity\AbstractCriteria;
use Ttskch\PaginatorBundle\Entity\Criteria;
use Ttskch\PaginatorBundle\Exception\UnexpectedCountTypeException;
use Ttskch\PaginatorBundle\Exception\UnexpectedSliceTypeException;

class Context
{
    /**
     * @var \ArrayIterator
     */
    public $slice;

    /**
     * @var int
     */
    public $count;

    /**
     * @var AbstractCriteria
     */
    public $criteria;

    /**
     * @var FormInterface
     */
    public $form;

    /**
     * @var Config
     */
    public $config;

    /**
     * @var Request|null
     */
    public $request;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(Config $config, RequestStack $requestStack, FormFactoryInterface $formFactory)
    {
        $this->config = $config;
        $this->request = $requestStack->getCurrentRequest();
        $this->formFactory = $formFactory;
    }

    public function initialize(string $sortKey = null, callable $slicer = null, callable $counter = null, AbstractCriteria $criteria = null, bool $handleRequest = true): void
    {
        $criteria = $criteria ?? new Criteria();
        $criteria->page = $criteria->page ?? 1;
        $criteria->limit = $criteria->limit ?? $this->config->limitDefault;
        $criteria->sort = $criteria->sort ?? $sortKey;
        $criteria->direction = $criteria->direction ?? $this->config->sortDirectionDefault;

        $this->criteria = $criteria;

        $this->form = $this->formFactory->createNamed('', $this->criteria->getFormTypeClass(), $this->criteria, [
            'method' => 'GET',
        ]);

        if ($handleRequest) {
            $this->handleRequest();
        }

        $this->slice = $slicer ? $slicer($this->criteria) : new \ArrayIterator();
        $this->count = $counter ? $counter($this->criteria) : 0;

        if (! $this->slice instanceof \ArrayIterator) {
            throw new UnexpectedSliceTypeException();
        }

        if (!is_int($this->count)) {
            throw new UnexpectedCountTypeException();
        }
    }

    public function handleRequest(): void
    {
        // Don't use Form::handleRequest() because it will clear properties corresponding empty queries.
        $this->form->submit($this->request ? $this->request->query->all() : null, false);
    }
}

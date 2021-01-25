<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Ttskch\PaginatorBundle\Entity\Criteria;
use Ttskch\PaginatorBundle\Form\CriteriaType;

class Context
{
    /**
     * @var \iterable
     */
    public $slice;

    /**
     * @var int
     */
    public $count;

    /**
     * @var Criteria
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

    public function initialize(string $sortKey = null, callable $slicer = null, callable $counter = null, string $criteriaClass = Criteria::class, string $formTypeClass = CriteriaType::class): void
    {
        $criteria = new $criteriaClass();

        if (! $criteria instanceof Criteria) {
            throw new \LogicException(sprintf('criteriaClass must be an instance of "%s"', Criteria::class));
        }

        $criteria->page = $criteria->page ?? 1;
        $criteria->limit = $criteria->limit ?? $this->config->limitDefault;
        $criteria->sort = $criteria->sort ?? $sortKey;
        $criteria->direction = $criteria->direction ?? $this->config->sortDirectionDefault;

        $this->criteria = $criteria;

        $this->form = $this->formFactory->createNamed('', $formTypeClass, $this->criteria, [
            'method' => 'GET',
        ]);

        $this->handleRequest();

        $this->slice = $slicer ? $slicer($this->criteria) : [];
        $this->count = $counter ? $counter($this->criteria) : 0;
    }

    public function handleRequest(): void
    {
        // Don't use Form::handleRequest() because it will clear properties corresponding empty queries.
        $this->form->submit($this->request ? $this->request->query->all() : null, false);
    }
}

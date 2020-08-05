<?php

declare(strict_types=1);

namespace Ttskch\PaginatorBundle;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Ttskch\PaginatorBundle\Entity\Criteria;
use Ttskch\PaginatorBundle\Form\CriteriaType;

class Context
{
    /**
     * @var array|\iterable
     */
    public $slice;

    /**
     * @var int
     */
    public $count;

    /**
     * @var Criteria|mixed
     */
    public $criteria;

    /**
     * @var Form
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

    public function initialize(string $sortKey, ?callable $slicer, ?callable $counter, string $criteriaClass = Criteria::class, string $formTypeClass = CriteriaType::class): void
    {
        $this->criteria = new $criteriaClass();
        $this->criteria->page = 1;
        $this->criteria->limit = $this->config->limitDefault;
        $this->criteria->sort = $sortKey;
        $this->criteria->direction = $this->config->sortDirectionDefault;

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

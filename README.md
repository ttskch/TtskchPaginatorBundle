# TtskchPaginatorBundle

[![](https://github.com/ttskch/TtskchPaginatorBundle/actions/workflows/ci.yaml/badge.svg?branch=main)](https://github.com/ttskch/TtskchPaginatorBundle/actions/workflows/ci.yaml?query=branch:main)
[![Latest Stable Version](https://poser.pugx.org/ttskch/paginator-bundle/version?format=flat-square)](https://packagist.org/packages/ttskch/paginator-bundle)
[![Total Downloads](https://poser.pugx.org/ttskch/paginator-bundle/downloads?format=flat-square)](https://packagist.org/packages/ttskch/paginator-bundle)

The most thin, simple and customizable paginator bundle for Symfony.

![](https://github.com/ttskch/TtskchPaginatorBundle/assets/4360663/b1e72eb4-ba61-4307-a153-8be46290cf27)

## Features

* So **light weight**
* **Well typed** (PHPStan level max)
* **Depends on nothing** other than Symfony and Twig
* But also easy to use with **Doctrine ORM**
* Of course **can paginate everything**
* Customizable **twig-templated views**
* Very easy-to-use **sortable link** feature
* Easy to use with **your own search form**
* Preset beautiful **Bootstrap4/5 theme**

## Requirements

* PHP: ^8.0
* Symfony: ^5.0|^6.0|^7.0

## Demo

👉 [Live demo is here](https://ttskchpaginatorbundle.herokuapp.com)

You can also see a sample code on [`demo/` directory](demo).

## Installation

```bash
$ composer require ttskch/paginator-bundle
```

```php
// config/bundles.php

return [
    // ...
    Ttskch\PaginatorBundle\TtskchPaginatorBundle::class => ['all' => true],
];
```

## Basic usages

### With Doctrine ORM

```php
// FooController.php

use Symfony\Component\HttpFoundation\Response;
use Ttskch\PaginatorBundle\Counter\Doctrine\ORM\QueryBuilderCounter;
use Ttskch\PaginatorBundle\Criteria\Criteria;
use Ttskch\PaginatorBundle\Paginator;
use Ttskch\PaginatorBundle\Slicer\Doctrine\ORM\QueryBuilderSlicer;

/**
 * @param Paginator<\Traversable<array-key, Foo>, Criteria> $paginator
 */
public function index(FooRepository $fooRepository, Paginator $paginator): Response
{
    $qb = $fooRepository->createQueryBuilder('f');
    $paginator->initialize(new QueryBuilderSlicer($qb), new QueryBuilderCounter($qb), new Criteria('id'));

    return $this->render('index.html.twig', [
        'foos' => $paginator->getSlice(),
    ]);
}
```

```twig
{# index.html.twig #}

<table>
  <thead>
  <tr>
    <th>{{ ttskch_paginator_sortable('id') }}</th>
    <th>{{ ttskch_paginator_sortable('name') }}</th>
    <th>{{ ttskch_paginator_sortable('email') }}</th>
  </tr>
  </thead>
  <tbody>
  {% for foo in foos %}
    <tr>
      <td>{{ foo.id }}</td>
      <td>{{ foo.name }}</td>
      <td>{{ foo.email }}</td>
    </tr>
  {% endfor %}
  </tbody>
</table>

{{ ttskch_paginator_pager() }}
```

See [src/Twig/TtskchPaginatorExtension.php](src/Twig/TtskchPaginatorExtension.php) to learn more about twig functions.

#### Sort with property of joined entity

Just do like as following.

```twig
{# index.html.twig #}

{# ... #}

<th>{{ ttskch_paginator_sortable('id') }}</th>
<th>{{ ttskch_paginator_sortable('name') }}</th>
<th>{{ ttskch_paginator_sortable('email') }}</th>
<th>{{ ttskch_paginator_sortable('bar.id', 'Bar') }}</th>
<th>{{ ttskch_paginator_sortable('bar.baz.id', 'Baz') }}</th>

{# ... #}
```

### With array

```php
// FooController.php

use Symfony\Component\HttpFoundation\Response;
use Ttskch\PaginatorBundle\Counter\ArrayCounter;
use Ttskch\PaginatorBundle\Criteria\Criteria;
use Ttskch\PaginatorBundle\Paginator;
use Ttskch\PaginatorBundle\Slicer\ArraySlicer;

/**
 * @param Paginator<array<array{id: int, name: string, email: string}>, Criteria> $paginator
 */
public function index(Paginator $paginator): Response
{
    $array = [
        ['id' => 1, 'name' => 'Tommy Yount', 'email' => 'tommy_yount@gmail.com'],
        ['id' => 2, 'name' => 'Hye Panter', 'email' => 'hye_panter@gmail.com'],
        ['id' => 3, 'name' => 'Vi Yohe', 'email' => 'vi_yohe@gmail.com'],
        ['id' => 4, 'name' => 'Keva Bandy', 'email' => 'keva_bandy@gmail.com'],
        ['id' => 5, 'name' => 'Hannelore Corning', 'email' => 'hannelore_corning@gmail.com'],
        ['id' => 6, 'name' => 'Delorse Whitcher', 'email' => 'delorse_whitcher@gmail.com'],
        ['id' => 7, 'name' => 'Katharyn Marrinan', 'email' => 'katharyn_marrinan@gmail.com'],
        ['id' => 8, 'name' => 'Jeannine Tope', 'email' => 'jeannine_tope@gmail.com'],
        ['id' => 9, 'name' => 'Jamila Braggs', 'email' => 'jamila_braggs@gmail.com'],
        ['id' => 10, 'name' => 'Eden Cunniff', 'email' => 'eden_cunniff@gmail.com'],
        // ...
        ['id' => 299, 'name' => 'Deshawn Kennedy', 'email' => 'deshawn_kennedy@gmail.com'],
        ['id' => 300, 'name' => 'Elenore Evens', 'email' => 'elenore_evens@gmail.com'],
    ];

    $paginator->initialize(
        new ArraySlicer($array),
        new ArrayCounter($array),
        new Criteria('id'),
    );

    return $this->render('index.html.twig', [
        'foos' => $paginator->getSlice(),
    ]);
}
```

### With something other data

Implement slicer and counter by yourself like as following.

```php
use Symfony\Component\HttpFoundation\Response;
use Ttskch\PaginatorBundle\Counter\CallbackCounter;
use Ttskch\PaginatorBundle\Criteria\Criteria;
use Ttskch\PaginatorBundle\Paginator;
use Ttskch\PaginatorBundle\Slicer\CallbackSlicer;

/**
 * @param Paginator<TypeOfYourOwnSlice>, Criteria> $paginator
 */
public function index(Paginator $paginator): Response
{
    $yourOwnData = /* ... */;

    $paginator->initialize(
        new CallbackSlicer(function (Criteria $criteria) use ($yourOwnData) {
            /* ... */
            return $yourOwnSlice;
        }),
        new CallbackCounter(function (Criteria $criteria) use ($yourOwnData) {
            /* ... */
            return $totalItemsCount;
        }),
        new Criteria('default_sort_key'),
    );

    return $this->render('index.html.twig', [
        'yourOwnSlice' => $paginator->getSlice(),
    ]);
}
```

## Configuring

```bash
$ bin/console config:dump-reference ttskch_paginator
# Default configuration for extension with alias: "ttskch_paginator"
ttskch_paginator:
    page:
        name:                 page
        range:                5
    limit:
        name:                 limit
        default:              10
    sort:
        key:
            name:                 sort
        direction:
            name:                 direction

            # "asc" or "desc"
            default:              asc
    template:
        pager:                '@TtskchPaginator/pager/default.html.twig'
        sortable:             '@TtskchPaginator/sortable/default.html.twig'
```

## Customizing views

### Using preset Bootstrap4/5 theme

Just configure bundle like below.

```yaml
# config/packages/ttskch_paginator.yaml

ttskch_paginator:
  template:
    pager: '@TtskchPaginator/pager/bootstrap5.html.twig'
#   pager: '@TtskchPaginator/pager/bootstrap4.html.twig'
```

### Using your own theme

Create your own templates and configure bundle like below.

```yaml
# config/packages/ttskch_paginator.yaml

ttskch_paginator:
  template:
    pager: 'your/own/pager.html.twig'
    sortable: 'your/own/sortable.html.twig'
```

## Using with search form

```php
// FooCriteria.php

use Ttskch\PaginatorBundle\Criteria\AbstractCriteria;

class FooCriteria extends AbstractCriteria
{
    public ?string $query = null;

    public function __construct(string $sort)
    {
        parent::__construct($sort);
    }

    public function getFormTypeClass(): string
    {
        return FooSearchType::class;
    }
}
```

```php
// FooSearchType.php

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ttskch\PaginatorBundle\Form\CriteriaType;

class FooSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', SearchType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FooCriteria::class,
            // if your app depends on symfony/security-csrf adding below is recommended
            // 'csrf_protection' => false,
        ]);
    }

    public function getParent(): string
    {
        return CriteriaType::class;
    }
}
```

```php
// FooRepository.php

use Doctrine\ORM\QueryBuilder;
use Ttskch\PaginatorBundle\Counter\Doctrine\ORM\QueryBuilderCounter;
use Ttskch\PaginatorBundle\Slicer\Doctrine\ORM\QueryBuilderSlicer;

public function sliceByCriteria(FooCriteria $criteria): \Traversable
{
    $qb = $this->createQueryBuilderFromCriteria($criteria);
    $slicer = new QueryBuilderSlicer($qb);

    return $slicer->slice($criteria);
}

public function countByCriteria(FooCriteria $criteria): int
{
    $qb = $this->createQueryBuilderFromCriteria($criteria);
    $counter = new QueryBuilderCounter($qb);

    return $counter->count($criteria);
}

private function createQueryBuilderFromCriteria(FooCriteria $criteria): QueryBuilder
{
    return $this->createQueryBuilder('f')
        ->orWhere('f.name like :query')
        ->orWhere('f.email like :query')
        ->setParameter('query', '%'.str_replace('%', '\%', $criteria->query).'%')
    ;
}
```

```php
// FooController.php

use Symfony\Component\HttpFoundation\Response;
use Ttskch\PaginatorBundle\Paginator;

/**
 * @param Paginator<\Traversable<array-key, Foo>, FooCriteria> $paginator
 */
public function index(FooRepository $fooRepository, Paginator $paginator): Response
{
    $paginator->initialize(
        \Closure::fromCallable([$fooRepository, 'sliceByCriteria']),
        \Closure::fromCallable([$fooRepository, 'countByCriteria']),
        // or if PHP >= 8.1
        // $fooRepository->sliceByCriteria(...),
        // $fooRepository->countByCriteria(...),
        new FooCriteria('id'),
    );

    return $this->render('index.html.twig', [
        'foos' => $paginator->getSlice(),
        'form' => $paginator->getForm()->createView(),
    ]);
}
```

```twig
{# index.html.twig #}

{{ form(form, {action: path('foo_index'), method: 'get'}) }}

<table>
    <thead>
    <tr>
        <th>{{ ttskch_paginator_sortable('id') }}</th>
        <th>{{ ttskch_paginator_sortable('name') }}</th>
        <th>{{ ttskch_paginator_sortable('email') }}</th>
    </tr>
    </thead>
    <tbody>
    {% for foo in foos %}
        <tr>
            <td>{{ foo.id }}</td>
            <td>{{ foo.name }}</td>
            <td>{{ foo.email }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ ttskch_paginator_pager() }}
```

## Using with joined query

```php
// FooRepository.php

use Doctrine\ORM\QueryBuilder;
use Ttskch\PaginatorBundle\Counter\Doctrine\ORM\QueryBuilderCounter;
use Ttskch\PaginatorBundle\Slicer\Doctrine\ORM\QueryBuilderSlicer;

public function sliceByCriteria(FooCriteria $criteria): \Traversable
{
    $qb = $this->createQueryBuilderFromCriteria($criteria);
    $slicer = new QueryBuilderSlicer($qb, alreadyJoined: true); // **PAY ATTENTION HERE**

    return $slicer->slice($criteria);
}

public function countByCriteria(FooCriteria $criteria): int
{
    $qb = $this->createQueryBuilderFromCriteria($criteria);
    $counter = new QueryBuilderCounter($qb);

    return $counter->count($criteria);
}

private function createQueryBuilderFromCriteria(FooCriteria $criteria): QueryBuilder
{
    return $this->createQueryBuilder('f')
        ->leftJoin('f.bar', 'bar')
        ->leftJoin('bar.baz', 'baz')
        ->orWhere('f.name like :query')
        ->orWhere('f.email like :query')
        ->orWhere('bar.name like :query')
        ->orWhere('baz.name like :query')
        ->setParameter('query', '%'.str_replace('%', '\%', $criteria->query).'%')
    ;
}
```

```php
// FooController.php

use Symfony\Component\HttpFoundation\Response;
use Ttskch\PaginatorBundle\Paginator;

/**
 * @param Paginator<\Traversable<array-key, Foo>, FooCriteria> $paginator
 */
public function index(FooRepository $fooRepository, Paginator $paginator): Response
{
    $paginator->initialize(
        \Closure::fromCallable([$fooRepository, 'sliceByCriteria']),
        \Closure::fromCallable([$fooRepository, 'countByCriteria']),
        // or if PHP >= 8.1
        // $fooRepository->sliceByCriteria(...),
        // $fooRepository->countByCriteria(...),
        new FooCriteria('f.id')
    );

    return $this->render('index.html.twig', [
        'foos' => $paginator->getSlice(),
        'form' => $paginator->getForm()->createView(),
    ]);
}
```

```twig
{# index.html.twig #}

{{ form(form, {action: path('foo_index'), method: 'get'}) }}

<table>
    <thead>
    <tr>
        <th>{{ ttskch_paginator_sortable('f.id', 'Id') }}</th>
        <th>{{ ttskch_paginator_sortable('f.name', 'Name') }}</th>
        <th>{{ ttskch_paginator_sortable('f.email', 'Email') }}</th>
        <th>{{ ttskch_paginator_sortable('bar.name', 'Bar') }}</th>
        <th>{{ ttskch_paginator_sortable('baz.name', 'Baz') }}</th>
    </tr>
    </thead>
    <tbody>
    {% for foo in foos %}
        <tr>
            <td>{{ foo.id }}</td>
            <td>{{ foo.name }}</td>
            <td>{{ foo.email }}</td>
            <td>{{ foo.bar.name }}</td>
            <td>{{ foo.bar.baz.name }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>

{{ ttskch_paginator_pager() }}
```

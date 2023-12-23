<?php

declare(strict_types=1);

namespace App\Criteria\Form;

use App\Criteria\PostCriteria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ttskch\PaginatorBundle\Form\CriteriaType;

class PostSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('query', SearchType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search for...',
                    'class' => 'w-100',
                ],
            ])
            ->add('after', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('before', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PostCriteria::class,
        ]);
    }

    public function getParent(): string
    {
        return CriteriaType::class;
    }
}

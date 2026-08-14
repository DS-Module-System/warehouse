<?php

namespace App\Form\Warehouse;

use App\Entity\Product\Product;
use App\Entity\Warehouse\Warehouse;
use App\Form\Core\DefaultForm\SearchForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WarehouseStockSearchForm extends SearchForm
{
    public function __construct(
        private RequestStack $requestStack,
        private UrlGeneratorInterface $router
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('warehouse', EntityType::class, [
                'label' => 'warehouse',
                'class' => Warehouse::class,
                'required' => false,
                'placeholder' => 'allWarehouses',
                'choice_label' => function (Warehouse $entity) {
                    return $entity->getName();
                },
                'attr' => [
                    'class' => 'select2',
                ]
            ])
            ->add('product', EntityType::class, [
                'label' => 'product',
                'class' => Product::class,
                'required' => false,
                'placeholder' => 'allProducts',
                'choice_label' => function (Product $entity) {
                    return $entity->getName();
                },
                'attr' => [
                    'class' => 'select2',
                ]
            ])
            ->add('quantityFrom', NumberType::class, [
                'required' => false,
                'label' => 'quantityFrom',
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                ],
            ])
            ->add('quantityTo', NumberType::class, [
                'required' => false,
                'label' => 'quantityTo',
                'scale' => 2,
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request) {
            $resolver->setDefault('action', $this->router->generate($request->get('_route'),
                array_merge($request->get('_route_params'), ['page'=>1])));
        }
        $resolver->setDefault('translation_domain', 'warehouse');
    }
} 
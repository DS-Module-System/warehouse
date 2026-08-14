<?php

namespace App\Form\Warehouse;

use App\Entity\Product\Product;
use App\Entity\Warehouse\Warehouse;
use App\Entity\Warehouse\WarehouseStock;
use App\Form\Core\DefaultForm\EditForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class WarehouseStockForm extends EditForm
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('warehouse', EntityType::class, [
                'class' => Warehouse::class,
                'label' => 'warehouse',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
                'choice_label' => function (Warehouse $entity) {
                    return $entity->getName();
                }, 
                'placeholder' => 'chooseWarehouse',
                'attr' => [
                    'class' => 'select2',
                ]
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'label' => 'product',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
                'choice_label' => function (Product $entity) {
                    return $entity->getName();
                },
                'placeholder' => 'chooseProduct',
                'attr' => [
                    'class' => 'select2',
                ]
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'quantity',
                'required' => true,
                'scale' => 2,
                'constraints' => [
                    new NotBlank(),
                    new PositiveOrZero(),
                ],
                'attr' => [
                    'step' => '0.01',
                    'min' => '0',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => WarehouseStock::class,
            'translation_domain' => 'warehouse',
        ]);
    }
} 
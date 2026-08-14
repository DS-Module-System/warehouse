<?php

namespace App\Form\Warehouse;

use App\Form\Core\DefaultForm\SearchForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class WarehouseSearchForm extends SearchForm
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
            ->add('name', TextType::class, [
                'required' => false,
                'label' => 'name'
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'label' => 'address'
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
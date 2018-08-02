<?php

namespace AppBundle\Form;

use AppBundle\Entity\ProductDetail;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductDetailType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('name')
                ->add('description', TextareaType::class)
                ->add('htmlDescription', TextareaType::class)
                ->add('brand')
                ->add('category')
                ->add('packageHeight')
                ->add('packageLength')
                ->add('packageWidth')
                ->add('dimUnit', ChoiceType::class, [
                    'choices' => [
                        'in' => 'in',
                        'cm' => 'cm',
                        'mm' => 'mm'
                    ]
                ])
                ->add('packageWeight')
                ->add('weightUnit', ChoiceType::class, [
                    'choices' => [
                        'lbs' => 'lbs',
                        'oz' => 'oz',
                        'kg' => 'kg',
                        'g' => 'g'
                    ]
                ])
                ->add('msrp')
                ->add('mapPrice')
                ->add('attributes', CollectionType::class, [
                    'label' => false,
                    'entry_type' => ProductAttributeType::class,
                    'entry_options' => ['label' => false],
                    'allow_add' => true,
                    'allow_delete' => true
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => ProductDetail::class
        ]);
    }

}

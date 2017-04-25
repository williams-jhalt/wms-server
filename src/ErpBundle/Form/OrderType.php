<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ErpBundle\Model\Order;

class OrderType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('webOrderNumber', TextType::class, array('required' => true))
                ->add('customerNumber', TextType::class, array('required' => true))
                ->add('shipToName', TextType::class, array('required' => true))
                ->add('shipToAddress1', TextType::class, array('required' => true))
                ->add('shipToAddress2', TextType::class)
                ->add('shipToAddress3', TextType::class)
                ->add('shipToCity', TextType::class, array('required' => true))
                ->add('shipToState', TextType::class)
                ->add('shipToZip', TextType::class)
                ->add('shipToCountry', TextType::class)
                ->add('shipToPhone', TextType::class)
                ->add('shipToEmail', TextType::class)
                ->add('residential', TextType::class)
                ->add('shipViaCode', TextType::class)
                ->add('items', CollectionType::class, array(
                    'entry_type' => OrderItemType::class,
                    'allow_add' => true)
        );
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => Order::class,
            'csrf_protection' => false
        ));
    }

    public function getName() {
        return 'order';
    }

}

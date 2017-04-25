<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use ErpBundle\Model\OrderItem;

class OrderItemType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('itemNumber', TextType::class)
                ->add('quantityOrdered', IntegerType::class);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => OrderItem::class,
            'csrf_protection' => false
        ));
    }

    public function getName() {
        return 'item';
    }

}

<?php

namespace App\Form;

use App\Entity\Ticket;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class,['attr' => ['id' => 'description']])
            ->add('start_at' , DateTimeType::class)
            ->add('end_at', DateTimeType::class)
            ->add('price', NumberType::class)
            ->add('nums_of_ticket', NumberType::class)
            ->add('test' , SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}

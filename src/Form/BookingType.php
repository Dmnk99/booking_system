<?php

namespace App\Form;

use App\Entity\Booking;
use App\Entity\Service;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User|null $user */
    $user = $options['user'] ?? null;

    $isAdmin = $user && in_array('ROLE_ADMIN', $user->getRoles(), true);
        
        $builder
            ->add('startTime', DateType::class, [
            'widget' => 'single_text',
            // prevents rendering it as type="date", to avoid HTML5 date pickers
            'html5' => false,
            'attr' => ['class' => 'js-datepicker'],])
            ->add('endTime', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('notes')
            ->add('createdAt')
            ->add('user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
'choice_label' => 'id',
            ]);
        if ($isAdmin) {
            $builder
            ->add('status', ChoiceType::class,[
                'choices' => [
                    'Pending' => 'Pending',
                    'Confirmed' => 'Confirmed',
                    'Canceled' => 'Canceled',
                    'Completed' => 'Completed',
                ],
                'disabled' =>!$isAdmin,
            ]);
    }
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'user' => null,
        ]);
    }
}

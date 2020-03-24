<?php

namespace App\Form;

use App\Entity\Paiement;
use App\Entity\Abonnement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Paiement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datePaiement',DateTimeType::class)
            ->add('montant', MoneyType::class )
            ->add('abonnement', EntityType::class, [
                // looks for choices from this entity
                'class' => Abonnement::class,

                // uses the Utilisateur.nomprenom property as the visible option string (propriété articificelle associée à getNomprenom
                'choice_label' => 'nomSignificatif',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}

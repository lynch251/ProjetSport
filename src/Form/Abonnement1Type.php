<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Offre;
use App\Entity\Utilisateur;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class Abonnement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateDebut', DateTimeType::class)
            ->add('dateFin', DateTimeType::class)
            ->add('dateValidite', DateTimeType::class)
            ->add('renouveler', ChoiceType::class, [
                'choices'  => [
                    'Oui' => 1,
                    'Non' => 0
                ]])
            ->add('offre', EntityType::class, [
                // looks for choices from this entity
                'class' => Offre::class,

                // uses the Offre.titre property as the visible option string
                'choice_label' => 'titre',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('client', EntityType::class, [
                // looks for choices from this entity
                'class' => Utilisateur::class,

                // uses the Utilisateur.nomprenom property as the visible option string (propriété articificelle associée à getNomprenom
                'choice_label' => 'nomprenom',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}

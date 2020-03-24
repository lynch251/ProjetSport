<?php

namespace App\Form;

use App\Entity\RoleUtilisateur;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('nomUtilisateur')
            ->add('prenomUtilisateur')
            ->add('email')
            ->add('password')
            ->add('role', EntityType::class, [
                // looks for choices from this entity
                'class' => RoleUtilisateur::class,

                // uses the Utilisateur.nomprenom property as the visible option string (propriété articificelle associée à getNomprenom
                'choice_label' => 'titre',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

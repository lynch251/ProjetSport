<?php
// src/Form/InscriptionFormulaire.php
namespace App\Form;

use App\Entity\RoleUtilisateur;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class ModifierInformationFormulaire extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, ['label' => 'Login'])
            ->add('nomUtilisateur', TextType::class, ['label' => 'Nom'])
            ->add('prenomUtilisateur', TextType::class, ['label' => 'Prénom'])
            ->add('email', EmailType::class , ['label' => 'Email'])
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
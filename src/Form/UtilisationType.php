<?php

namespace App\Form;

use App\Entity\Machine;
use App\Entity\Offre;
use App\Entity\Seance;
use App\Entity\Utilisation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('duree')
            ->add('quantite')
            ->add('ordre')
            ->add('seance', EntityType::class, [
                'class' => Seance::class,
                'choice_label' => 'intitule',
            ])
            ->add('machine', EntityType::class, [
                'class' => Machine::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisation::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Tache;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la tâche',
                'attr' => ['placeholder' => 'Entrez le nom de la tâche']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Entrez une description']
            ])
            ->add('categorie', TextType::class, [
                'label' => 'Catégorie',
                'attr' => ['placeholder' => 'Entrez la catégorie']
            ])
            ->add('statut', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'À faire' => 'a_faire', 
                    'En cours' => 'en_cours',
                    'Terminé' => 'termine',
                ],
                'choice_label' => function($choice, $key, $value) {
                    return $key;
                },
            ])
            ->add('priorite', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Basse' => 'basse',
                    'Moyenne' => 'moyenne',
                    'Haute' => 'haute',
                ],
                'choice_label' => function($choice, $key, $value) {
                    return $key; 
                },
            ])
            ->add('maxDate', DateTimeType::class, [
                'label' => 'Date limite',
                'widget' => 'single_text',
            ])
            ->add('createdAt', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'disabled' => true,
            ])
            ->add('updatedAt', DateTimeType::class, [
                'label' => 'Date de mise à jour',
                'widget' => 'single_text',
                'disabled' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}

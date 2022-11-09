<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class ModifyAccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Mon Adresse email'
            ])
            ->add('name',TextType::class,[
                'label'=>'Mon nom'
            ])
            ->add('firstname', TextType::class,[
                'label' => 'Mon prénom'
            ])
            ->add('pseudo', TextType::class)
            ->add('city', TextType::class, [
                'label'=> 'Ma Ville'
            ])
        
            ->add('phoneNumber', TelType::class,[
                'label'=> 'Votre numéro de Téléphone'
            ])
            ->add('Country',CountryType::class,[
                'label'=> 'Votre pays'
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false, // Tell that there is no Entity to link
                'required' => false
                
              ])
              ->add('photo_background', FileType::class, [
                'label' => 'Photo de couverture',
                'required' => false,
                'mapped' => false
                
                
              ])
            ->add('Valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'disabled' =>true,
            'label' => 'Mon Adresse email'
        ])
        ->add('old_password', PasswordType::class, [
            'label' => 'Mon mot de passe actuel',
            'mapped' => false,
            'attr' => [
                'placeholder'=> 'Veuillez saisir votre mot de passe actuel'
            ],
        ])
        ->add('new_password', RepeatedType::class,
        [   'type'=> PasswordType::class,
            'mapped' => false, // indique que la propriété n'existe pas en BDD
            'invalid_message'=> 'Les mots de passes ne sont pas identiques',
            'label' => ' Votre Mot de passe', 
            'required' => true,
            'first_options'=> ['label'=> 'Mon nouveau Mot de passe'],
            'second_options' =>[ 'label'=> 'Confirmez votre nouveau mot de passe'],
        'attr' => [
            'placeholder' => 'Veuillez saisir votre nouveau Mot de Passe.']])
        
        ->add('firstname', TextType::class,[
            'disabled' => true,
            'label' => 'Mon prénom'
        ])
        ->add('name', TextType::class,[
            'disabled' => true,
            'label' => 'Mon nom'
        ])
        ->add('submit', SubmitType::class)
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

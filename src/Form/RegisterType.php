<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,
            ['label' => ' Votre E-mail', 
            'attr' => [
                'placeholder' => 'Veuillez saisir votre E-mail',
                'class'=>  'form-control']])
            ->add('password', RepeatedType::class,
            [   'type'=> PasswordType::class,
                'invalid_message'=> 'Les mots de passes ne sont pas identiques',
                'label' => ' Votre Mot de passe', 
                'required' => true,
                
                'first_options' => ['label' => 'Mot de passe : ', 'attr' => ['class' => 'form-control'], 'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'match' => true,
                        'message' => 'Le mot de passe doit contenir : minimum 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                    
                    ]),
                ]],

                'second_options' => ['label' => 'Répéter le mot de passe : ', 'attr' => ['class' => 'form-control'], 'constraints' => [
                    new Regex([
                        'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                        'match' => true,
                        'message' => 'Le mot de passe doit contenir : minimum 8 caractère, un nombre, une minuscule, une majuscule et un caractère spécial',
                    ]),
                ]],
                ])

            ->add('pseudo', TextType::class,[
                'label' => 'Votre pseudo',
                'constraints'=> new Length([
                    'min'=>2,
                    'max'=>25
                ]),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre prénom']
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de profil',
                'mapped' => false, // Tell that there is no Entity to link
                'required' => true,
                'attr' => [
                    'class'=>  'form-control'],
                'constraints'=>[
                    new Image([
                        'maxSize'=> '8M',
                        'maxSizeMessage'=> 'La taille de l\'image est trop grande.
                        La taille maximal est de {{limit}} {{suffix}}',
                        'mimeTypes'=> [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'image/webp',
                        ],
                        'mimeTypesMessage'=>'Format Invalide ({{ type }}).
                        Les formats autorisés sont : {{ type }}',
                    ]),
               
            ]
                
            ])
            ->add('roleArtiste', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'je suis un Artiste',
                'attr' => [
                    'class'=>  'form-control'],
            ])
            ->add('roleUser', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Je suis un Mélomane',
                'attr' => [
                    'class'=>  'form-control'],
            ])
            ->add('cgu',CheckboxType::class,[
                'mapped'=>false,
                'required'=>true,
                'label' => "En cochant cette case j'accepte les CGU",
                'attr' => [
                    'class'=>  'form-control'],
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

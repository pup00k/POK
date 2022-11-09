<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Place;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AddEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            
           
            ->add('name',TextType::class)
            ->add('photo', FileType::class, [
                'label' => false,
                'multiple'=> false,
                'mapped' => false, // Tell that there is no Entity to link
                'required' => false,
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
            ->add('date_start',DateTimeType::class,[
                'widget' => 'single_text'
            ])
            ->add('date_end',DateTimeType::class,[
                'widget' => 'single_text'
            ])
            ->add('price', MoneyType::class)

            ->add('name_location', TextType::class,[
                'label'=> 'Nom du lieu'
            ])
            ->add('cp_location', TextType::class,[
                'label'=> 'Code postal'
            ])
            ->add('city_location', TextType::class,[
                'label'=> 'Ville'
            ])

            ->add('categorie_event', EntityType::class,[
                // looks for choices from this entity
                'class' => Category::class,
                'multiple'=>true,
                // uses the User.username property as the visible option string
                'choice_label' => 'Name_category'])
            ->add('description', TextType::class, [
                'attr' => [
                    'placeholder'=> 'Veuillez saisir une description de votre évenement.'
                ],
            ])
            ->add('nbPlace', IntegerType::class,[
                'label'=> 'Nombre de place',
                'required' =>true,
                'attr' => [
                    'placeholder'=> 'Veuillez entrez le nombre de place limité a votre évènement.'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}

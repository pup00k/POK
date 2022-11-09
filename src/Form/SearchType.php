<?php

namespace App\Form;

use App\Classe\Search;
use App\Data\SearchData;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class SearchType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
    ->add('q', TextType::class,[
        'label' => 'Rechercher',
        'required'=>false,
        'attr'=> [
            'placeholder'=>'Votre recherche...',
            'class'=> 'form-control-sm'
        ]

    ])
    ->add('categories', EntityType::class,[
        'required'=> false,
        'class'=>Category::class,
        'choice_label'=> "Name_category",
        'multiple'=> true, 
        // 'expanded'=> true
    ])
    ->add('dateStart', DateTimeType::class,[
        'label'=> false,
        'widget' => 'single_text',
        'required' => false, 
    
    ])
    // ->add('dateEnd', DateTimeType::class,[
    //     'label'=> false,
    //     'widget' => 'single_text',
    //     'required' => false, 
    
    // ])
    ->add('min', NumberType::class,[
        'label'=> false,
        'required'=> false,
        'attr' => [
            'placeholder'=> 'Prix Minimum'
        ]
    ])
    ->add('max', NumberType::class,[
        'label'=> false,
        'required'=> false,
        'attr' => [
            'placeholder'=> 'Prix Maximum'
        ]
    ])
    ->add('submit', SubmitType::class,[
        'label' => 'Filtrer',
    ])
    ;
    }

    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> SearchData::class,
            'method'=> 'GET',
            'csrf_protection'=>false
        ]);
    }


    public function getBlockPrefix(){

        return "";
    }
   }


    
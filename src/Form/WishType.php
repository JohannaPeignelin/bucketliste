<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => ['class' =>'form-control']
            ])
            ->add('description', TextareaType::class,  [
                'required' => false,
                'label' => 'Describe your wish man : ',
                'attr'=> ['class'=>'form-control']
            ])
            ->add('author', TextType::class, [
                'attr'=> ['class'=>'form-control']
            ])
            ->add('category', EntityType::class, [
                //quelle entité est liée
                'class'=>Category::class,
                //quel attribut servira à afficher l'information
                'choice_label'=>'name',
                'expanded' => true,
                'multiple' => true,
                'query_builder'=>function(CategoryRepository $categoryRepository){
                    $qb=$categoryRepository->createQueryBuilder('c');
                    $qb->addOrderBy('c.name', 'ASC');
                    return $qb;
                },
                'attr'=> ['class'=>'category-list']


            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
            //desactive la verif HTML pour tous les champs
            'required' => false
        ]);
    }
}

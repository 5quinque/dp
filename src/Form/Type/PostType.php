<?php
namespace App\Form\Type;

use App\Entity\Media;
use App\Entity\Post;
use App\Entity\Tag;
use App\Repository\MediaRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('information', TextareaType::class)
            ->add('media', EntityType::class, [
                'class' => Media::class,
                // Only show media that aren't currently assigned to a post
                'query_builder' => function (MediaRepository $vr) {
                    return $vr->createQueryBuilder('v')
                    ->andWhere('v.post is NULL');
                },
                'choice_label' => 'filename',
                'multiple' => true,
                'required' => false,
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false,
                // 'choices' => [],
            ])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

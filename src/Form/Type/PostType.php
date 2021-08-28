<?php
namespace App\Form\Type;

use App\Entity\Video;
use App\Repository\VideoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('information', TextareaType::class)
            ->add('videos', EntityType::class, [
                // looks for choices from this entity
                'class' => Video::class,

                // Only show videos that aren't currently assigned to a post
                'query_builder' => function (VideoRepository $vr) {
                    return $vr->createQueryBuilder('v')
                    ->andWhere('v.post is NULL');
                },

                'choice_label' => 'filename',
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                // 'expanded' => true,
                'required'   => false,
            ])
            ->add('save', SubmitType::class)
        ;
    }
}

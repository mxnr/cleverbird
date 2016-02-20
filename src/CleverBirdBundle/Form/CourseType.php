<?php

namespace CleverBirdBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use CleverBirdBundle\Entity\Course;

class CourseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Main Statuses' => [
                        'Draft' => Course::DRAFT,
                        'Public' => Course::FOR_ALL,
                        'Private (join by invite)' => Course::INVITE,
                    ],
                ],
                'label' => 'Course type',
            ])
            ->add('image')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CleverBirdBundle\Entity\Course',
        ));
    }
}

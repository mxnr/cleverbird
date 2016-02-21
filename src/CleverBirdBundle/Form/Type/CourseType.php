<?php

namespace CleverBirdBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use CleverBirdBundle\Entity\CourseStatuses;

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
                        'Self-Paced' => CourseStatuses::SELF_PACED,
                        'Time Driven' => CourseStatuses::TIME_DRIVEN,
                        'Finished' => CourseStatuses::FINISHED,
                    ],
                ],
                'label' => 'Course type',
            ])
            ->add('accessType', ChoiceType::class, [
                'choices' => [
                    'Main Statuses' => [
                        'Draft' => CourseStatuses::DRAFT,
                        'Public' => CourseStatuses::FOR_ALL,
                        'Private (join by invite)' => CourseStatuses::INVITE,
                    ],
                ],
                'label' => 'Course access',
            ])
            ->add('startDate', DateTimeType::class, ['widget' => 'single_text', 'html5' => false])
            ->add('endDate', DateTimeType::class, ['widget' => 'single_text', 'html5' => false])
            ->add('image')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CleverBirdBundle\Entity\Course',
        ]);
    }
}

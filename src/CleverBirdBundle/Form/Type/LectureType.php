<?php

namespace CleverBirdBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use CleverBirdBundle\Entity\LectureStatuses;

class LectureType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Draft' => LectureStatuses::DRAFT,
                    'Available' => LectureStatuses::AVAILABLE,
                ],
                'label' => 'Access',
            ])
            ->add('accessType', ChoiceType::class, [
                'choices' => [
                    'No one can\'t contribute' => LectureStatuses::NO_ONE,
                    'Only users from your group' => LectureStatuses::GROUP_CONTRIBUTE,
                    'All users' => LectureStatuses::ALL_CONTRIBUTE,
                ],
                'label' => 'Contribution type',
            ])
            ->add('title')
            ->add('text', TextareaType::class, ['attr' => ['class' => 'editable']])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'CleverBirdBundle\Entity\Lecture',
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Field;
use App\Enum\FieldTypeEnum;
use App\EventSubscriber\FormSelectFieldSubscriber;
use App\Form\DataTransformer\StringToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FieldType extends AbstractType
{
    public function __construct(
        private StringToArrayTransformer $stringToArrayTransformer,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Nom'
            ))
            ->add('type', ChoiceType::class, [
                "label" => "Type du champs",
                "choices" => [
                    'Texte' => FieldTypeEnum::TEXT->value,
                    'Email' => FieldTypeEnum::EMAIL->value,
                    'Tél' => FieldTypeEnum::PHONE->value,
                    'Lien' => FieldTypeEnum::URL->value,
                    'Nombre' => FieldTypeEnum::NUMBER->value,
                    'Date' => FieldTypeEnum::DATE->value,
                    'Code secret' => FieldTypeEnum::SECRET_CODE->value,
                    'Liste déroulante' => FieldTypeEnum::SELECT->value,
                    'Text Long' => FieldTypeEnum::TEXTAREA->value,
                ]
            ])
            ->add('possibleOptions', TextType::class, [
                'label' => 'Choix possibles',
                'attr' => [
                    'data-tags' => true
                ],
                'row_attr' => [
                    'class' => 'd-none'
                ],
                'required' => false
            ])
            ->add('required', CheckboxType::class, [
                'label' => 'Obligatoire',
                'required' => false
            ])
            ->add('multiple', CheckboxType::class, [
                'label' => 'Multiple',
                'row_attr' => [
                    'class' => 'd-none',
                ],
                'required' => false,
            ])
            ->add('placeholder', TextType::class, [
                'label' => 'Placeholder',
                'required' => false,
            ])
            ->add('defaultValue', TextType::class, [
                'label' => 'Valeur par défault',
                'required' => false
            ]);

        $builder->get('possibleOptions')->addModelTransformer($this->stringToArrayTransformer);
        $builder->addEventSubscriber(new FormSelectFieldSubscriber($builder, $this->stringToArrayTransformer));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
           'data_class' => Field::class,
        ]);
    }
}

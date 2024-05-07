<?php

namespace App\EventSubscriber;

use App\Entity\Field;
use App\Enum\FieldTypeEnum;
use App\Form\DataTransformer\StringToArrayTransformer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;

class FormSelectFieldSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly FormBuilderInterface $builder,
        private readonly StringToArrayTransformer $stringToArrayTransformer
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmitForm',
        ];
    }

    public function preSetData(PreSetDataEvent $event): void
    {
        if ($event->getData() instanceof Field) {
            /**
             * @var Field $field
             */
            $field = $event->getData();
            $form = $event->getForm();

            if ($field->getType() === FieldTypeEnum::SELECT->value && $field->getId()) {
                $form->add(
                    $this->builder->create(
                        'possibleOptions', TextType::class,
                        [
                            'label' => 'Choix possibles',
                            'attr' => [
                                'data-tags' => true
                            ],
                            'required' => true,
                            'auto_initialize' => false,
                        ]
                    )->addModelTransformer($this->stringToArrayTransformer)->getForm()
                )
                    ->add('multiple', CheckboxType::class, [
                        'label' => 'Multiple',
                        'required' => false,
                    ]);
            }
        }
    }

    public function preSubmitForm(PreSubmitEvent $event): void
    {
        /**
         * @var Field $field
         */
        $field = $event->getData();

        if (!$field) {
            return;
        }

        if (isset($field['type']) && $field['type'] !== FieldTypeEnum::SELECT->value) {
            $field['possibleOptions'] = null;
            $field['multiple'] = false;
        }

        $event->setData($field);
    }
}

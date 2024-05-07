<?php

namespace App\Provider;

use App\Entity\Field;
use App\Enum\FieldTypeEnum;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

final class FormFieldTypeProvider
{
    /**
     * @param Field $field
     * @return \Closure
     */
    public static function getBuiltInTypeByField(Field $field): \Closure {
        $options = [
            'attr' => [
                'placeholder' => $field->getPlaceholder() ?? ''
            ],
            'row_attr' => [
                'class' => 'form-group col-md-6'
            ],
            'required' => $field->isRequired(),
        ];

        return match ($field->getType()) {
            FieldTypeEnum::TEXT->value => static function () use($options) {
                return [
                    'class' => TextType::class,
                    'options' => $options
                ];
            },
            FieldTypeEnum::NUMBER->value => static function () use($options) {
                return [
                    'class' => NumberType::class,
                    'options' => $options
                ];
            },
            FieldTypeEnum::SELECT->value => static function() use($field, $options) {
                $possiblesOptions = $field->getPossibleOptions();
                $options['choices'] = array_combine($possiblesOptions, $possiblesOptions);

                if ($field->isMultiple()) {
                    $options['expanded'] = true;
                    $options['multiple'] = true;
                }

                return [
                    'class' => ChoiceType::class,
                    'options' => $options
                ];
            },
            FieldTypeEnum::TEXTAREA->value => static function () use($options) {
                $options['attr']['rows'] = 8;

                return [
                    'class' => TextareaType::class,
                    'options' => $options
                ];
            },
            FieldTypeEnum::PHONE->value => static function () use($options) {
                return [
                    'class' => TelType::class,
                    'options' => $options
                ];
            },
            FieldTypeEnum::EMAIL->value => static function () use($options) {
                return [
                    'class' => EmailType::class,
                    'options' => $options
                ];
            },
            FieldTypeEnum::URL->value => static function () use($options) {
                return [
                    'class' => UrlType::class,
                    'options' => $options
                ];
            },
            FieldTypeEnum::DATE->value => static function () use($options) {
                $options['widget'] = 'single_text';

                return [
                    'class' => DateType::class,
                    'options' => $options
                ];
            },
            FieldTypeEnum::SECRET_CODE->value => static function () use($options) {
                return [
                    'class' => PasswordType::class,
                    'options' => $options
                ];
            },
            default => throw new \InvalidArgumentException("Invalid type"),
        };
    }
}

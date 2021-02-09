<?php
declare(strict_types=1);

namespace FrontendBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Trait FormTypeTrait
 * @package FrontendBundle\Form
 */
trait FormTypeTrait
{
    private function fieldMapper(FormBuilderInterface $builder, array $fieldsOption): FormBuilderInterface
    {
        foreach ($fieldsOption as $property => $fieldOptions) {
            $builder->add($property, $fieldOptions[self::FORM_TYPE_PROPERTY], $fieldOptions[self::FORM_OPTIONS_PROPERTY]);
        }

        return $builder;
    }

    private function getFieldProperty(string $formType, bool $formDisable = false, bool $required = true, array $extraOptions = []): array
    {
        $options = [];

        if ($this->isAllowedForOptions($formType)) {
            $options = [
                self::FORM_ATTR_PROPERTY => [
                    self::FORM_READONLY_PROPERTY => $formDisable,
                ],
                self::FORM_REQUIRE_PROPERTY => $required,
            ];
        }

        if ($extraOptions !== []) {
            $options = array_merge($options, $extraOptions);
        }

        return [
            self::FORM_TYPE_PROPERTY => $formType,
            self::FORM_OPTIONS_PROPERTY => $options,
        ];
    }

    private function getFieldPropertyChoiceType(
        array $choicesArray,
        bool $formDisable = false,
        bool $choseTranslationDomain = true,
        bool $choiceValue = false,
        bool $expanded = false,
        bool $required = true
    ): array {
        $options = [
            self::FORM_CHOICES_PROPERTY => $choicesArray,
            self::FORM_CHOICES_TRAN_DOM_PROPERTY => $choseTranslationDomain,
            self::FORM_EXPANDED_PROPERTY => $expanded,
            self::FORM_REQUIRE_PROPERTY => $required,
            self::FORM_ATTR_PROPERTY => [
                self::FORM_READONLY_PROPERTY => $formDisable,
            ],
        ];

        if ($choiceValue) {
            $options = array_merge($options, [
                self::FORM_CHOICES_VALUE_PROPERTY => function ($choice) {
                    return $choice;
                },
            ]);
        }

        return [
            self::FORM_TYPE_PROPERTY => ChoiceType::class,
            self::FORM_OPTIONS_PROPERTY => $options,
        ];
    }

    private function registrationFormHelper(string $paramString, array $paramsArray): array
    {
        $data = [];

        foreach ($paramsArray as $param) {
            if ($param === '') {
                $param = 'default';
            }

            $data[sprintf('%s.%s', $paramString, $param)] = $param;
        }

        return $data;
    }

    private function isAllowedForOptions(string $fieldType): bool
    {
        return !in_array($fieldType, self::FORM_NOT_ALLOWED_FOR_OPTIONS);
    }

    private function checkForSetData(array $options, string $elementForCheck, string $instance): bool
    {
        return $options[self::FORM_DATA_PROPERTY][$elementForCheck] instanceof $instance;
    }
}

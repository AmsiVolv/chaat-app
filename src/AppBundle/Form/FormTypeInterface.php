<?php
declare(strict_types=1);

namespace FrontendBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Interface FormTypeInterface
 * @package FrontendBundle\Form
 */
interface FormTypeInterface
{
    public const FORM_NOT_ALLOWED_FOR_OPTIONS = [
        SubmitType::class,
        ButtonType::class,
    ];

    public const FORM_TYPE_PROPERTY = 'type';
    public const FORM_OPTIONS_PROPERTY = 'options';
    public const FORM_READONLY_PROPERTY = 'readonly';
    public const FORM_ATTR_PROPERTY = 'attr';
    public const FORM_REQUIRE_PROPERTY = 'required';
    public const FORM_CHOICES_PROPERTY = 'choices';
    public const FORM_CHOICES_TRAN_DOM_PROPERTY = 'choice_translation_domain';
    public const FORM_CHOICES_VALUE_PROPERTY = 'choice_value';
    public const FORM_EXPANDED_PROPERTY = 'expanded';
    public const FORM_DATA_PROPERTY = 'data';
}

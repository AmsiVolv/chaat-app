<?php
declare(strict_types=1);

namespace FrontendBundle\Form\RegistrationForm;

use FrontendBundle\Form\FormTypeTrait;
use FrontendBundle\Form\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegistrationFormStepOneType
 * @package FrontendBundle\Form\RegistrationForm
 */
class RegistrationFormStepPersonalDataType extends AbstractType implements FormTypeInterface
{
    public const FORM_NAME = 'registration_form_step_personal_data';

    public const FIELD_NAME = 'name';
    public const FIELD_SURNAME = 'surname';
    public const FIELD_PATRONYMIC = 'patronymic';
    public const FIELD_INN = 'inn';
    public const FIELD_DATE_OF_BIRTH = 'dateOfBirth';
    public const FIELD_PHONE = 'phone';

    public const FIELD_EMAIL = 'email';
    public const FIELD_PASSWORD = 'password';

    public const FIELD_MARKETING_APPROVE = 'marketingApprove';
    public const FIELD_PROCESS_DATA_APPROVE = 'processDataApprove';

    public const FIELD_AMOUNT = 'amount';
    public const FIELD_DAYS = 'days';

    private const FIELD_SAVE = 'save';

    use FormTypeTrait;

    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    private function getInitData(bool $formDisable = false): array
    {
        return [
            self::FIELD_AMOUNT => $this->getFieldProperty(HiddenType::class),
            self::FIELD_DAYS => $this->getFieldProperty(HiddenType::class),
            self::FIELD_NAME => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_SURNAME => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_PATRONYMIC => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_INN => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_DATE_OF_BIRTH => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_PHONE => $this->getFieldProperty(TelType::class, $formDisable),
            self::FIELD_EMAIL => $this->getFieldProperty(EmailType::class, $formDisable),
            self::FIELD_PASSWORD => $this->getFieldProperty(RepeatedType::class, $formDisable, true, [self::FORM_TYPE_PROPERTY => PasswordType::class]),
            self::FIELD_MARKETING_APPROVE => $this->getFieldProperty(CheckboxType::class, false, false),
            self::FIELD_PROCESS_DATA_APPROVE => $this->getFieldProperty(CheckboxType::class, false, false),

            self::FIELD_SAVE => $this->getFieldProperty(SubmitType::class),
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $initData = $this->getInitData();

        $this->fieldMapper($builder, $initData);

        $builder->setData([
            self::FIELD_AMOUNT => $options[self::FORM_DATA_PROPERTY][self::FIELD_AMOUNT],
            self::FIELD_DAYS => $options[self::FORM_DATA_PROPERTY][self::FIELD_DAYS],
            self::FIELD_MARKETING_APPROVE => true,
        ]);
    }
}

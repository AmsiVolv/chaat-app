<?php
declare(strict_types=1);

namespace FrontendBundle\Form\RegistrationForm;

use CoolCredit\ServiceBundle\Assembler\Registration\RegistrationAssembler;
use CoolCredit\ServiceBundle\Dto\Registration\RegistrationDto;
use FrontendBundle\Form\FormTypeInterface;
use FrontendBundle\Form\FormTypeTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegistrationFormStepPassportType
 * @package FrontendBundle\Form\RegistrationForm
 */
class RegistrationFormStepPassportType extends AbstractType implements FormTypeInterface
{
    public const FORM_NAME = 'registration_form_step_passport';

    public const IS_PASSPORT_NEW_FORMAT = 'isPassportNewFormat';

    /** New format properties */
    public const NEW_PASSPORT_NUMBER = 'newPassportNumber';
    public const NEW_PASSPORT_RECORD_NUMBER = 'newPassportRecordNumber';
    public const NEW_PASSPORT_ISSUE_DATE = 'newPassportIssueDate';
    public const NEW_PASSPORT_ISSUED_AUTHORITY = 'newAuthorityIssuedPassport';

    /** Old format properties */
    public const OLD_PASSPORT_NUMBER = 'oldPassportNumber';
    public const OLD_PASSPORT_ISSUE_DATE = 'oldPassportIssueDate';
    public const OLD_PASSPORT_ISSUED_AUTHORITY = 'oldAuthorityIssuedPassport';

    private const FIELD_BACK = 'back';
    private const FIELD_SAVE = 'save';

    use FormTypeTrait;

    private TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    private function getInitData(bool $formDisable = false): array
    {
        return [
          self::IS_PASSPORT_NEW_FORMAT => $this->getFieldProperty(CheckboxType::class, $formDisable, false),

            self::NEW_PASSPORT_NUMBER => $this->getFieldProperty(TextType::class, $formDisable),
            self::NEW_PASSPORT_RECORD_NUMBER => $this->getFieldProperty(TextType::class, $formDisable),
            self::NEW_PASSPORT_ISSUE_DATE => $this->getFieldProperty(TextType::class, $formDisable),
            self::NEW_PASSPORT_ISSUED_AUTHORITY => $this->getFieldProperty(TextType::class, $formDisable),

            self::OLD_PASSPORT_NUMBER => $this->getFieldProperty(TextType::class, $formDisable),
            self::OLD_PASSPORT_ISSUE_DATE => $this->getFieldProperty(TextType::class, $formDisable),
            self::OLD_PASSPORT_ISSUED_AUTHORITY => $this->getFieldProperty(TextType::class, $formDisable),

            self::FIELD_BACK => $this->getFieldProperty(ButtonType::class),
            self::FIELD_SAVE => $this->getFieldProperty(SubmitType::class),
        ];
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $initData = $this->getInitData();

        $data = [];

        if ($this->checkForSetData($options, RegistrationAssembler::REGISTRATION_DTO, RegistrationDto::class)) {
            /** @var RegistrationDto $registrationDto */
            $registrationDto = $options[self::FORM_DATA_PROPERTY][RegistrationAssembler::REGISTRATION_DTO];

            $data = [
                self::IS_PASSPORT_NEW_FORMAT => $registrationDto->isPassportNewFormat(),

                self::NEW_PASSPORT_NUMBER => $registrationDto->getIdCard(),
                self::NEW_PASSPORT_RECORD_NUMBER => '', //$registrationDto //TODO
                self::NEW_PASSPORT_ISSUE_DATE => $registrationDto->getPassportIssueDate(),
                self::NEW_PASSPORT_ISSUED_AUTHORITY => $registrationDto->getAuthorityIssuedPassport(),

                self::OLD_PASSPORT_NUMBER => $registrationDto->getIdCard(),
                self::OLD_PASSPORT_ISSUE_DATE => $registrationDto->getPassportIssueDate(),
                self::OLD_PASSPORT_ISSUED_AUTHORITY => $registrationDto->getAuthorityIssuedPassport(),
            ];
        }

        $builder->setData($data);
        $this->fieldMapper($builder, $initData);
    }
}

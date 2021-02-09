<?php
declare(strict_types=1);

namespace FrontendBundle\Form\RegistrationForm;

use CoolCredit\InfrastructureBundle\Doctrine\Type\RegistrationAddressRegionType;
use CoolCredit\InfrastructureBundle\Doctrine\Type\RegistrationHousingType;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationAddressRegion;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationHousing;
use CoolCredit\ServiceBundle\Assembler\Address\AddressAssembler;
use CoolCredit\ServiceBundle\Assembler\Registration\RegistrationAssembler;
use CoolCredit\ServiceBundle\Dto\Address\AddressDto;
use CoolCredit\ServiceBundle\Dto\Registration\RegistrationDto;
use FrontendBundle\Form\FormTypeTrait;
use FrontendBundle\Form\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegistrationFormStepTwoType
 * @package FrontendBundle\Form\RegistrationForm
 */
class RegistrationFormStepAddressType extends AbstractType implements FormTypeInterface
{
    public const FORM_NAME = 'registration_form_step_address';

    public const FIELD_REGION = 'region';
    public const FIELD_CITY = 'city';
    public const FIELD_STREET = 'street';
    public const FIELD_STREET_NUM = 'streetNumber';
    public const FIELD_ZIPCODE = 'zipcode';
    public const FIELD_REGISTER_DATE = 'registerDate';

    public const FIELD_DEL_REGION = 'delRegion';
    public const FIELD_DEL_CITY = 'delCity';
    public const FIELD_DEL_STREET = 'delStreet';
    public const FIELD_DEL_STREET_NUM = 'delStreetNumber';
    public const FIELD_DEL_ZIPCODE = 'delZipcode';

    public const FIELD_HOUSING = 'housing';
    public const FIELD_DEL_ADDRESS_SWITCHER = 'delAddressSwitcher';

    private const FIELD_SAVE = 'save';

    use FormTypeTrait;

    private TranslatorInterface $translator;

    public function __construct(
        TranslatorInterface $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $initData = $this->getInitData();
        $data = [];

        if ($this->checkForSetData($options, AddressAssembler::ADDRESS_DTO, AddressDto::class)) {
            /** @var AddressDto $addressDto */
            $addressDto = $options[self::FORM_DATA_PROPERTY][AddressAssembler::ADDRESS_DTO];
            $data = [
                self::FIELD_REGION => $addressDto->getRegion(),
                self::FIELD_CITY => $addressDto->getCity(),
                self::FIELD_STREET => $addressDto->getStreet(),
                self::FIELD_STREET_NUM => $addressDto->getStreetNumber(),
                self::FIELD_ZIPCODE => $addressDto->getZipCode(),
                self::FIELD_REGISTER_DATE => $addressDto->getResidenceRegisterDate(),
            ];

            if ($addressDto->hasDeliveryAddress()) {
                $data = array_merge($data, [
                    self::FIELD_DEL_ADDRESS_SWITCHER => $addressDto->hasDeliveryAddress(),
                    self::FIELD_DEL_REGION => $addressDto->getDelRegion(),
                    self::FIELD_DEL_CITY => $addressDto->getDelCity(),
                    self::FIELD_DEL_STREET => $addressDto->getDelStreet(),
                    self::FIELD_DEL_STREET_NUM => $addressDto->getDelStreetNumber(),
                    self::FIELD_DEL_ZIPCODE => $addressDto->getDelZipCode(),
                ]);
            }
        }

        if ($this->checkForSetData($options, RegistrationAssembler::REGISTRATION_DTO, RegistrationDto::class)) {
            /** @var RegistrationDto $registrationDto */
            $registrationDto = $options[self::FORM_DATA_PROPERTY][RegistrationAssembler::REGISTRATION_DTO];

            $data = array_merge($data, [
               self::FIELD_HOUSING => $registrationDto->getHousing(),
            ]);
        }

        $builder->setData($data);
        $this->fieldMapper($builder, $initData);
    }

    private function getInitData(bool $formDisable = false): array
    {
        $housingTypes = $this->registrationFormHelper(RegistrationHousing::FORM_NAME, RegistrationHousingType::getChoices());
        $regions = $this->registrationFormHelper(RegistrationAddressRegion::FORM_NAME, RegistrationAddressRegionType::getChoices());

        return [
            self::FIELD_CITY => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_STREET => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_STREET_NUM => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_ZIPCODE => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_REGISTER_DATE => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_DEL_CITY => $this->getFieldProperty(TextType::class, $formDisable, false),
            self::FIELD_DEL_STREET => $this->getFieldProperty(TextType::class, $formDisable, false),
            self::FIELD_DEL_STREET_NUM => $this->getFieldProperty(TextType::class, $formDisable, false),
            self::FIELD_DEL_ZIPCODE => $this->getFieldProperty(TextType::class, $formDisable, false),

            self::FIELD_DEL_ADDRESS_SWITCHER => $this->getFieldProperty(CheckboxType::class, $formDisable, false),
            self::FIELD_HOUSING => $this->getFieldPropertyChoiceType($housingTypes, $formDisable, true, true),
            self::FIELD_REGION => $this->getFieldPropertyChoiceType($regions, $formDisable, true, true),
            self::FIELD_DEL_REGION => $this->getFieldPropertyChoiceType($regions, $formDisable, true, true),

            self::FIELD_SAVE => $this->getFieldProperty(SubmitType::class),
        ];
    }
}

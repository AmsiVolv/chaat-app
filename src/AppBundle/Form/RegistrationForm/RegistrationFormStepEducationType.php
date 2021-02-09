<?php
declare(strict_types=1);

namespace FrontendBundle\Form\RegistrationForm;

use CoolCredit\InfrastructureBundle\Doctrine\Type\RegistrationEducationFacultyType;
use CoolCredit\InfrastructureBundle\Doctrine\Type\RegistrationEducationType;
use CoolCredit\InfrastructureBundle\Doctrine\Type\RegistrationEmployerContractType;
use CoolCredit\InfrastructureBundle\Doctrine\Type\RegistrationSalaryPeriodicityType;
use CoolCredit\InfrastructureBundle\Doctrine\Type\RegistrationWorkingIndustryType;
use CoolCredit\InfrastructureBundle\Doctrine\Type\RegistrationWorkingYearsType;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationEducation;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationEducationFaculty;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationEmployerContract;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationSalaryPeriodicity;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationWorkingIndustry;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationWorkingYears;
use CoolCredit\ServiceBundle\Assembler\Registration\RegistrationAssembler;
use CoolCredit\ServiceBundle\Dto\Registration\RegistrationDto;
use FrontendBundle\Form\FormTypeTrait;
use FrontendBundle\Form\FormTypeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegistrationFormStepEducationType
 * @package FrontendBundle\Form\RegistrationForm
 */
class RegistrationFormStepEducationType extends AbstractType implements FormTypeInterface
{
    public const FORM_NAME = 'registration_form_step_education';

    public const FIELD_EMPLOYMENT_TYPE = 'employmentType';
    public const FIELD_NET_INCOME = 'netIncome';
    public const FIELD_COST = 'costs';
    public const FIELD_EMPLOYER_NAME = 'employerName';
    public const FIELD_EMPLOYER_PHONE = 'employerPhone';
    public const FIELD_JOB_TITLE = 'jobTitle';
    public const FIELD_FACULTY = 'faculty';

    public const FIELD_WORKING_YEARS = 'workingYears';
    public const FIELD_EDUCATION = 'education';
    public const FIELD_EMPLOYER_CONTRACT = 'employerContract';
    public const FIELD_INDUSTRY = 'industry';
    public const FIELD_SALARY_PERIODICITY = 'salaryPeriodicity';

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
        $workingYears = $this->registrationFormHelper(RegistrationWorkingYears::FORM_NAME, RegistrationWorkingYearsType::getChoices());
        $employerContracts = $this->registrationFormHelper(RegistrationEmployerContract::FORM_NAME, RegistrationEmployerContractType::getChoices());
        $education = $this->registrationFormHelper(RegistrationEducation::FORM_NAME, RegistrationEducationType::getChoices());
        $industry = $this->registrationFormHelper(RegistrationWorkingIndustry::FORM_NAME, RegistrationWorkingIndustryType::getChoices());
        $salaryPeriodicity = $this->registrationFormHelper(RegistrationSalaryPeriodicity::FORM_NAME, RegistrationSalaryPeriodicityType::getChoices());
        $faculty = $this->registrationFormHelper(RegistrationEducationFaculty::FORM_NAME, RegistrationEducationFacultyType::getChoices());

        return [
            self::FIELD_EMPLOYMENT_TYPE => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_JOB_TITLE => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_NET_INCOME => $this->getFieldProperty(IntegerType::class, $formDisable),
            self::FIELD_COST => $this->getFieldProperty(IntegerType::class, $formDisable),
            self::FIELD_EMPLOYER_NAME => $this->getFieldProperty(TextType::class, $formDisable),
            self::FIELD_EMPLOYER_PHONE => $this->getFieldProperty(TelType::class, $formDisable),

            self::FIELD_EDUCATION => $this->getFieldPropertyChoiceType($education, $formDisable, true, true),
            self::FIELD_WORKING_YEARS => $this->getFieldPropertyChoiceType($workingYears, $formDisable, true, true),
            self::FIELD_SALARY_PERIODICITY => $this->getFieldPropertyChoiceType($salaryPeriodicity, $formDisable, true, true),
            self::FIELD_EMPLOYER_CONTRACT => $this->getFieldPropertyChoiceType($employerContracts, $formDisable, true, true),
            self::FIELD_INDUSTRY => $this->getFieldPropertyChoiceType($industry, $formDisable, true, true),
            self::FIELD_FACULTY => $this->getFieldPropertyChoiceType($faculty, $formDisable, true, true, false, false),

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
                //self::FIELD_EMPLOYMENT_TYPE => $registrationDto,         TODO employmentType, employerContract co nechat, co odstranit?
                self::FIELD_JOB_TITLE => $registrationDto->getEmployerPosition(),
                self::FIELD_NET_INCOME => $registrationDto->getNetIncome(),
                self::FIELD_COST => $registrationDto->getCosts(),
                self::FIELD_EMPLOYER_NAME => $registrationDto->getEmployerName(),
                self::FIELD_EMPLOYER_PHONE => $registrationDto->getEmployerPhoneNumber(),

                self::FIELD_EDUCATION => $registrationDto->getEducation(),
                self::FIELD_WORKING_YEARS => $registrationDto->getWorkingYears(),
                self::FIELD_SALARY_PERIODICITY => $registrationDto->getSalaryPeriodicity(),
                self::FIELD_EMPLOYER_CONTRACT => $registrationDto->getEmployerContract(),
                self::FIELD_INDUSTRY => $registrationDto->getWorkingIndustry(),
                self::FIELD_FACULTY => $registrationDto->getFaculty(),
            ];
        }

        $builder->setData($data);
        $this->fieldMapper($builder, $initData);
    }
}

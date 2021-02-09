<?php
declare(strict_types=1);

namespace FrontendBundle\Form;

use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationEmployerContract;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationHousing;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationMaritalStatus;
use CoolCredit\ModelBundle\Entity\Registration\PersonalInfo\RegistrationWorkingYears;
use CoolCredit\ServiceBundle\Dto\Preregistration\PreregistrationDto;
use CoolCredit\ServiceBundle\Service\BankService;
use CoolCredit\ServiceBundle\Service\MoneySourceService;
use CoolCredit\ServiceBundle\Service\NationalityService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegistrationType
 * @package FrontendBundle\Form
 */
class RegistrationType extends AbstractType
{
    private TranslatorInterface $translator;
    private BankService $bankService;
    private MoneySourceService $moneySourceService;
    private NationalityService $nationalityService;

    public function __construct(
        TranslatorInterface $translator,
        BankService $bankService,
        MoneySourceService $moneySourceService,
        NationalityService $nationalityService
    ) {
        $this->translator = $translator;
        $this->bankService = $bankService;
        $this->moneySourceService = $moneySourceService;
        $this->nationalityService = $nationalityService;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $housingTypes = [
            'registration.housing.' . RegistrationHousing::STATE_OWNER => RegistrationHousing::STATE_OWNER,
            'registration.housing.' . RegistrationHousing::STATE_PARENTS => RegistrationHousing::STATE_PARENTS,
            'registration.housing.' . RegistrationHousing::STATE_PARTNER => RegistrationHousing::STATE_PARTNER,
            'registration.housing.' . RegistrationHousing::STATE_SUBLET => RegistrationHousing::STATE_SUBLET,
        ];

        $workingYears = [
            'registration.workingyears.' . RegistrationWorkingYears::LESS_THAN_3_MONTHS => RegistrationWorkingYears::LESS_THAN_3_MONTHS,
            'registration.workingyears.' . RegistrationWorkingYears::LESS_THAN_1_YEAR => RegistrationWorkingYears::LESS_THAN_1_YEAR,
            'registration.workingyears.' . RegistrationWorkingYears::BETWEEN_1_TO_2_YEARS => RegistrationWorkingYears::BETWEEN_1_TO_2_YEARS,
            'registration.workingyears.' . RegistrationWorkingYears::BETWEEN_3_TO_5_YEARS => RegistrationWorkingYears::BETWEEN_3_TO_5_YEARS,
            'registration.workingyears.' . RegistrationWorkingYears::MORE_THAN_5_YEARS => RegistrationWorkingYears::MORE_THAN_5_YEARS,
        ];

        $employerContracts = [
            'registration.employercontract.' . RegistrationEmployerContract::INDEFINITE_PERIOD => RegistrationEmployerContract::INDEFINITE_PERIOD,
            'registration.employercontract.' . RegistrationEmployerContract::FIXED_PERIOD => RegistrationEmployerContract::FIXED_PERIOD,
        ];

        $maritalStatuses = [
            'registration.maritalstatus.' . RegistrationMaritalStatus::SINGLE => RegistrationMaritalStatus::SINGLE,
            'registration.maritalstatus.' . RegistrationMaritalStatus::MARRIED => RegistrationMaritalStatus::MARRIED,
            'registration.maritalstatus.' . RegistrationMaritalStatus::DIVORCED => RegistrationMaritalStatus::DIVORCED,
            'registration.maritalstatus.' . RegistrationMaritalStatus::WIDOWER => RegistrationMaritalStatus::WIDOWER,
            'registration.maritalstatus.' . RegistrationMaritalStatus::PARTNERSHIP => RegistrationMaritalStatus::PARTNERSHIP,
        ];

        $minorChildrens = [
            'registration.minorChildrens.0' => 0,
            'registration.minorChildrens.1' => 1,
            'registration.minorChildrens.2' => 2,
            'registration.minorChildrens.3' => 3,
            'registration.minorChildrens.4' => 4,
            'registration.minorChildrens.more' => 5,
        ];

        $builder
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('nin', TelType::class)
            ->add(
                'nationality',
                ChoiceType::class,
                [
                    'choices' => $this->getNationalityArray(),
                    'choice_translation_domain' => false,
                ]
            )
            ->add('idCardNumber', TextType::class)
            ->add('email', EmailType::class)
            ->add('phoneNumber', TelType::class)
            ->add('street', TextType::class)
            ->add('streetNumber', TextType::class)
            ->add('city', TextType::class)
            ->add('zipCode', TextType::class)
            ->add('deladdress_switcher', CheckboxType::class, ['required' => false])
            ->add('deliveryStreet', TextType::class, ['required' => false])
            ->add('deliveryStreetNumber', TextType::class, ['required' => false])
            ->add('deliveryCity', TextType::class, ['required' => false])
            ->add('deliveryZipCode', TextType::class, ['required' => false])
            ->add('accountNumber', TelType::class)
            ->add(
                'bank',
                ChoiceType::class,
                [
                    'choices' => $this->getBanksArray(),
                    'choice_translation_domain' => false,
                ]
            )
            ->add(
                'maritalStatus',
                ChoiceType::class,
                [
                    'choices' => $maritalStatuses,
                    'choice_value' => function ($choice) {
                        return $choice;
                    },
                    'expanded' => true,
                ]
            )
            ->add(
                'minorChildren',
                ChoiceType::class,
                [
                    'choices' => $minorChildrens,
                    'choice_value' => function ($choice) {
                        return $choice;
                    },
                    'expanded' => true,
                    'required' => false,
                ]
            )
            ->add(
                'housing',
                ChoiceType::class,
                [
                    'choices' => $housingTypes,
                    'choice_value' => function ($choice) {
                        return $choice;
                    },
                    'expanded' => true,
                ]
            )
            ->add(
                'moneySource',
                ChoiceType::class,
                [
                    'choices' => $this->getMoneySourceArray(),
                    'choice_translation_domain' => false,
                    'expanded' => true,
                    'choice_value' => function ($choice) {
                        return $choice;
                    },
                ]
            )
            ->add('netIncome', TelType::class)
            ->add('costs', TelType::class)
            ->add(
                'workingYears',
                ChoiceType::class,
                [
                    'choices' => $workingYears,
                    'choice_value' => function ($choice) {
                        return $choice;
                    },
                    'required' => false,
                    'expanded' => true,
                ]
            )
            ->add('employer', TextType::class, ['required' => false])
            ->add('employerIc', HiddenType::class, ['required' => false])
            ->add(
                'employerContract',
                ChoiceType::class,
                [
                    'choices' => $employerContracts,
                    'choice_value' => function ($choice) {
                        return $choice;
                    },
                    'expanded' => true,
                    'required' => false,
                ]
            )
            ->add('selfEmployerIc', TextType::class, ['required' => false])
            ->add('selfEmployerSphere', TextType::class, ['required' => false])
            ->add('selfEmployerPlace', TextType::class, ['required' => false])
            ->add('otherIncome', TextType::class, ['required' => false]);

        if (key_exists('data', $options) &&
            key_exists('freeLoanStatusValue', $options['data']) &&
            key_exists('isMorePayments', $options['data']) &&
            $options['data']['freeLoanStatusValue'] == 1 && $options['data']['isMorePayments'] == 0) {
            $builder->add('promoCode', HiddenType::class, ['required' => false]);
        } else {
            $builder->add('promoCode', TextType::class, ['required' => false]);
        }

        $builder
            ->add('approval', CheckboxType::class)
            ->add(
                'approval4',
                CheckboxType::class,
                [
                    'required' => false,
                ]
            )
            ->add('hash', HiddenType::class)
            ->add('save', SubmitType::class);

        if (key_exists('data', $options) &&
            key_exists('preregistration', $options['data']) &&
            $options['data']['preregistration'] instanceof PreregistrationDto
        ) {
            /** @var PreregistrationDto $dto */
            $dto = $options['data']['preregistration'];
            $delAddress = $dto->isNotEmptyDeliveryAddress();

            $builder->setData([
                'name' => $dto->getName(),
                'surname' => $dto->getSurname(),
                'email' => $dto->getEmail(),
                'phoneNumber' => $dto->getPhoneNumber(),
                'nin' => $dto->getIdentificationNumber(),
                'idCardNumber' => $dto->getIdCard(),
                'street' => $dto->getStreet(),
                'streetNumber' => $dto->getStreetNumber(),
                'city' => $dto->getCity(),
                'zipCode' => $dto->getZipCode(),
                'deliveryStreet' => $dto->getDelStreet(),
                'deliveryStreetNumber' => $dto->getDelStreetNumber(),
                'deliveryCity' => $dto->getDelCity(),
                'deliveryZipCode' => $dto->getDelZipCode(),
                'accountNumber' => $dto->getAccountNumber(),
                'bank' => $dto->getBankId(),
                'maritalStatus' => $dto->getMaritalStatus(),
                'minorChildren' => $dto->getMinorChildren() ?? 0,
                'housing' => $dto->getHousing(),
                'moneySource' => $dto->getMoneySource(),
                'nationality' => $dto->getNationalityId(),
                'netIncome' => $dto->getNetIncome(),
                'costs' => $dto->getCosts(),
                'workingYears' => $dto->getWorkingYears(),
                'employer' => $dto->getEmployer(),
                'employerIc' => $dto->getEmployerIc(),
                'employerContract' => $dto->getEmployerContract(),
                'selfEmployerIc' => $dto->getSelfEmployerIc(),
                'selfEmployerSphere' => $dto->getSelfEmployerSphere(),
                'selfEmployerPlace' => $dto->getSelfEmployerPlace(),
                'otherIncome' => $dto->getOtherIncome(),
                'promoCode' => $dto->getPromoCode(),
                'deladdress_switcher' => $delAddress,
                'approval4' => !$dto->isNoPromoSms(),
                'approval' => $dto->isApproval(),
                'hash' => $dto->getHash(),
            ]);
        } else {
            $builder->setData([
                'minorChildren' => 0,
            ]);
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    private function getBanksArray(): array
    {
        $bankPaymentNowLater = [];
        $bankPaymentNow = [];
        $banksArray = [];

        $bankPayments = $this->bankService->getAllForPayment();
        foreach ($bankPayments as $bankPayment) {
            $bankPaymentNow[$bankPayment->getCode() . ' (' . $bankPayment->getName() . ')'] = $bankPayment->getId();
        }

        $banksArray['Půjčujeme ihned'] = $bankPaymentNow;

        $bankNonPayments = $this->bankService->getAllForNonPayment();
        foreach ($bankNonPayments as $bankPayment) {
            $bankPaymentNowLater[$bankPayment->getCode() . ' (' . $bankPayment->getName() . ')'] = $bankPayment->getId();
        }

        $banksArray['Jiné banky (půjčujeme do druhého pracovního dne)'] = $bankPaymentNowLater;

        return $banksArray;
    }

    private function getMoneySourceArray(): array
    {
        $data = [];

        $moneySources = $this->moneySourceService->getAllActive();
        foreach ($moneySources as $moneySource) {
            $data[$moneySource->getName()] = $moneySource->getId();
        }

        return $data;
    }

    private function getNationalityArray(): array
    {
        $data = [];

        $nationalities = $this->nationalityService->getAll();
        foreach ($nationalities as $nationality) {
            $data[$nationality->getName()] = $nationality->getId();
        }

        return $data;
    }
}

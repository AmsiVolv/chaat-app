<?php
declare(strict_types=1);

namespace FrontendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class RegistrationFormType
 * @package FrontendBundle\Form
 */
class RegistrationFormType extends AbstractType
{
    /**
     * RegistrationFormType constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setAction($options['data']['url'])
            ->add('amount', HiddenType::class)
            ->add('days', HiddenType::class)
            ->add('save', SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}

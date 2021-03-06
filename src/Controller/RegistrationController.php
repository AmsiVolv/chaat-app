<?php
declare(strict_types=1);

namespace App\Controller;

use App\Assembler\UserDtoAssembler;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\AppAuthenticatorAuthenticator;
use App\Services\UserService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private UserService $userService;
    private UserDtoAssembler $userAssembler;

    public function __construct(
        EmailVerifier $emailVerifier,
        UserService $userService,
        UserDtoAssembler $userAssembler
    ) {
        $this->emailVerifier = $emailVerifier;
        $this->userService = $userService;
        $this->userAssembler = $userAssembler;
    }

    /**
     * @Route("/registration", name="registration")
     * @param Request $request
     * @param GuardAuthenticatorHandler $guardHandler
     * @param AppAuthenticatorAuthenticator $authenticator
     * @return Response
     */
    public function registrationAction(Request $request, GuardAuthenticatorHandler $guardHandler, AppAuthenticatorAuthenticator $authenticator): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userDto = $this->userAssembler->toUserDtoFromRequest($request);
            $user = $this->userService->storeFromUserDto($userDto);

            // encode the plain password

            // generate a signed url and email it to the user
//            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
//                (new TemplatedEmail())
//                    ->from(new Address('info@cb.cz', 'Chat-Bot V�E'))
//                    ->to($user->getEmail())
//                    ->subject('Please Confirm your Email')
//                    ->htmlTemplate('registration/confirmation_email.html.twig')
//            );
            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}

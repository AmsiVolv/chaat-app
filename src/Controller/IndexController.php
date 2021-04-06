<?php
declare(strict_types=1);

namespace App\Controller;

use DateTime;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    private string $jwtToken;

    public function __construct(string $jwtToken)
    {
        $this->jwtToken = $jwtToken;
    }

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $username = $this->getUser()->getUsername();

        $key = InMemory::plainText('chat-app'); // don't forget to set this parameter! Test value: !ChangeMe!
        $configuration = Configuration::forSymmetricSigner(new \Lcobucci\JWT\Signer\Hmac\Sha256(), $key);

        $token = $configuration->builder()
            ->withClaim('mercure', ['subscribe' => [sprintf('%s', $username)]]) // can also be a URI template, or *
            ->getToken($configuration->signer(), $configuration->signingKey())
            ->toString();

        $response =  $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);

        $cookie = Cookie::create('mercureAuthorization')
            ->withValue($token)
            ->withExpires((new DateTime())->add(
                new \DateInterval('PT2H')
            ))
            ->withPath('/.well-known/mercure')
            ->withSecure(true)
            ->withHttpOnly(true)
            ->withSameSite('strict');

        $response->headers->setCookie($cookie);

        return $response;
    }
}

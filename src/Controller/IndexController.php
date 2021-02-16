<?php
declare(strict_types=1);

namespace App\Controller;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\WebLink\Link;

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
        $hubUrl = $this->getParameter('mercure.default_hub');
        $this->addLink($request, new Link('mercure', $hubUrl));

        $key = InMemory::plainText($this->jwtToken);
        $configuration = Configuration::forSymmetricSigner(new Sha256(), $key);
dd($key);
        $token = $configuration->builder()
            ->withClaim('mercure', ['subscribe' => ["http://example.com/books/1"]]) // can also be a URI template, or *
            ->getToken($configuration->signer(), $configuration->signingKey())
            ->toString();

        dd($token);

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}

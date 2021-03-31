<?php
declare(strict_types=1);

namespace App\Controller;

use App\Services\UserService;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Post("/api/users", name="users")
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function getUsers(Request $request, SerializerInterface $serializer): Response
    {
        $users =  $this->userService->getUsernameFromRequest($request, $this->getUser()->getId());

        $usersSerialized = $serializer->serialize($users, 'json', [
            'attributes' => ['id','username'],
        ]);

        $response = new Response();

        $response->setContent($usersSerialized);

        return $response;
    }
}

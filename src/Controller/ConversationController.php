<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\ConversationRepository;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\WebLink\Link;

/**
 * Class ConversationController
 * @package App\Controller
 */
class ConversationController extends AbstractController
{
    private string $mercureDefaultHub;

    private ConversationRepository $conversationRepository;

    public function __construct(
        string $mercureDefaultHub,
        ConversationRepository $conversationRepository
    ) {
        $this->mercureDefaultHub = $mercureDefaultHub;
        $this->conversationRepository = $conversationRepository;
    }


    /**
     * @Get("/conversations", name="conversations")
     * @param Request $request
     * @return Response
     */
    public function getConversationAction(Request $request): Response
    {
        $user = $this->getUser()->getId();
        $conversation = $this->conversationRepository->findConversationsByUser($user);

        $this->addLink($request, new Link('mercure', $this->mercureDefaultHub));

        return $this->json($conversation);
    }

    /**
         * @Get("/conversation", name="conversation")t
     * @param Request $request
     * @param PublisherInterface $publisher
     * @return Response
     */
    public function index(Request $request, PublisherInterface $publisher): Response
    {
        $update = new Update(
            '/chat',
            json_encode(['message' => 'Hi'])
        );

        // The Publisher service is an invokable object
        $publisher($update);

        return $this->render('conversation/index.html.twig', [
            'controller_name' => 'ConversationController',
        ]);
    }
}

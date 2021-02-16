<?php

namespace App\Controller;

use App\Repository\ConversationRepository;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\WebLink\Link;

class ConversationController extends AbstractController
{
    private string $mercureDefaultHub;

    private ConversationRepository $conversationRepository;

    public function __construct(string $mercureDefaultHub,
     ConversationRepository $conversationRepository
    ) {
        $this->mercureDefaultHub = $mercureDefaultHub;
        $this->conversationRepository = $conversationRepository;
    }

    /**
     * @Get("/conversation", name="conversation")t
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $conversation = $this->conversationRepository->findConversationsByUser($this->getUser()->getId());
        $this->addLink($request, new Link($this->mercureDefaultHub));

        return $this->render('conversation/index.html.twig', [
            'controller_name' => 'ConversationController',
        ]);
    }
}

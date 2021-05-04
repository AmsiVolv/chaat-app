<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\Feedback;
use App\Factory\FeedbackFactory;
use App\Repository\FeedbackRepository;
use App\Repository\UserRepository;
use stdClass;
use Throwable;

/**
 * Class FeedbackService
 * @package App\Services
 */
class FeedbackService
{
    private UserRepository $userRepository;
    private FeedbackRepository $feedbackRepository;

    public function __construct(UserRepository $userRepository, FeedbackRepository $feedbackRepository)
    {
        $this->userRepository = $userRepository;
        $this->feedbackRepository = $feedbackRepository;
    }

    /**
     * @param stdClass $data
     * @param int $userId
     * @return Feedback
     * @throws Throwable
     */
    public function saveFeedbackFromRequest(stdClass $data, int $userId): Feedback
    {
        $user = $this->userRepository->getUserById($userId);
        $feedback = FeedbackFactory::create($data, $user);

        return $this->feedbackRepository->store($feedback);
    }
}

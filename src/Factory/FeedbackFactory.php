<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Feedback;
use App\Entity\User;
use JetBrains\PhpStorm\Pure;
use stdClass;

/**
 * Class FeedbackFactory
 * @package App\Factory
 */
class FeedbackFactory
{
    #[Pure] public static function create(stdClass $feedback, User $user): Feedback
    {
        return new Feedback(
            $feedback->feedback['comment'],
            $feedback->feedback['rate'],
            $user
        );
    }
}

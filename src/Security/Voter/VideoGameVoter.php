<?php

namespace App\Security\Voter;

use App\Model\Entity\User;
use App\Model\Entity\VideoGame;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @template TAttribute of string
 * @template TSubject of mixed
 * @phpstan-extends Voter<'read'|'edit', VideoGame>
 */
class VideoGameVoter extends Voter
{
    public const REVIEW = 'review';

    /**
     * @param TAttribute $attribute
     * @param TSubject $subject
     */
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::REVIEW && $subject instanceof VideoGame;
    }

    /**
     * @param TAttribute $attribute
     * @param TSubject $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return !$subject->hasAlreadyReview($user);
    }
}

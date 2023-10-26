<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    public const EDIT = 'movie.edit';
    public const VIEW = 'movie.view';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return \in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Movie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        return match ($attribute) {
            self::VIEW => $this->checkView($subject, $user),
            self::EDIT => $this->checkEdit($subject, $user),
            default => false,
        };

    }

    protected function checkView(Movie $movie, User $user): bool
    {
        if ('G' === $movie->getRated()) {
            return true;
        }

        $age = $user->getBirthday()?->diff(new \DateTimeImmutable())->y ?? null;

        return match ($movie->getRated()) {
            'PG', 'PG-13' => $age && $age >= 13,
            'R', 'NC-17' => $age && $age >= 17,
            default => false,
        };
    }

    protected function checkEdit(Movie $movie, User $user): bool
    {
        return $this->checkView($movie, $user) && $movie->getCreatedBy() === $user;
    }
}

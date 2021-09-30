<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['USER_EDIT', 'USER_DELETE'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $subject;
        $userApi = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$userApi instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'USER_EDIT':
            case 'USER_DELETE':
                if (in_array('ROLE_ADMIN', $userApi->getRoles())) {
                    return true;
                }

                // Handle owner
                if ($userApi->getUser() === $user) {
                    return true;
                }
        }

        return false;
    }
}

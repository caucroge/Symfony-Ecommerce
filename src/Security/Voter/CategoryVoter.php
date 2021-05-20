<?php

namespace App\Security\Voter;

use App\Repository\CategoryRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CategoryVoter extends Voter
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['CAN_EDIT'])
            && is_numeric($subject);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $category = $this->categoryRepository->find($subject);
        if (!$category) {
            return false;
        }

        switch ($attribute) {
            case 'CAN_EDIT':
                return $category->getOwner() === $user;
        }

        return false;
    }
}

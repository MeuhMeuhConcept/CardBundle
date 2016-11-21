<?php

namespace MMC\CardBundle\Security\Voter;

use MMC\CardBundle\Entity\Card;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CardVoter extends Voter
{
    const VIEW = 'view';
    const VIEW_VALID = 'view_valid';
    const VIEW_DRAFT = 'view_draft';

    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::VIEW_DRAFT, self::VIEW_VALID])) {
            return false;
        }

        // only vote on Card objects inside this voter
        if (!$subject instanceof Card) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        switch ($attribute) {
            case self::VIEW:
                return $this->canView($subject, $token);
            case self::VIEW_VALID:
                return $this->canViewValid($subject, $token);
            case self::VIEW_DRAFT:
                return $this->canViewDraft($subject, $token);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Card $subject, TokenInterface $token)
    {
        if ($this->canViewValid($subject, $token)) {
            return true;
        }

        return $this->canViewDraft($subject, $token);
    }

    private function canViewValid(Card $subject, TokenInterface $token)
    {
        if ($subject->getValid()) {
            return true;
        }

        return false;
    }

    private function canViewDraft(Card $subject, TokenInterface $token)
    {
        if ($this->decisionManager->decide($token, ['ROLE_ADMIN']) && $subject->getDraft()) {
            return true;
        }

        return false;
    }
}

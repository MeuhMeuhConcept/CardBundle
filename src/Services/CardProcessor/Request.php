<?php

namespace MMC\CardBundle\Services\CardProcessor;

use MMC\CardBundle\Entity\Card;
use MMC\CardBundle\Entity\CardItem;
use MMC\CardBundle\Model\Action;

class Request
{
    protected $card;

    protected $cardItem;

    protected $className;

    protected $action;

    public function __construct($cardOrItemOrClassname, $action)
    {
        if ($cardOrItemOrClassname instanceof CardItem) {
            $this->className = get_class($cardOrItemOrClassname->getCard());
            $this->card = $cardOrItemOrClassname->getCard();
            $this->cardItem = $cardOrItemOrClassname;
        } elseif ($cardOrItemOrClassname instanceof Card) {
            $this->className = get_class($cardOrItemOrClassname);
            $this->card = $cardOrItemOrClassname;
        } elseif (is_string($cardOrItemOrClassname)) {
            $this->className = $cardOrItemOrClassname;
        } else {
            throw new \Exception('Request : Bad attribut !');
        }

        if (!Action::isValidValue($action)) {
            throw new \InvalidArgumentException('Action invalid', 1);
        }

        $this->action = $action;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getCard()
    {
        return $this->card;
    }

    public function getCardItem()
    {
        return $this->cardItem;
    }

    public function getAction()
    {
        return $this->action;
    }
}

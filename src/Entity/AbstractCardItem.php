<?php

namespace MMC\CardBundle\Entity;

use MMC\CardBundle\Model\Status;

abstract class AbstractCardItem implements CardItem
{
    abstract public function getSupportedCardClass();

    abstract public function duplicate();

    protected $status;

    protected $card;

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        if (!Status::isValidValue($status)) {
            throw new \InvalidArgumentException('Invalid Status');
        }

        $this->status = $status;

        return $this;
    }

    public function getCard()
    {
        return $this->card;
    }

    public function setCard(Card $card = null)
    {
        $supported = $this->getSupportedCardClass();
        if ($card !== null && !$card instanceof $supported) {
            throw new \InvalidArgumentException('The card is not a valid Card (only '.$this->getSupportedCardClass().' supported)');
        }

        if ($this->card && $this->card != $card) {
            $this->card->removeItem($this);
        }

        if ($card && $this->card != $card) {
            $this->card = $card;

            $card->addItem($this);
        }

        return $this;
    }

    public function getValidationGroups()
    {
        return ['Default', 'validate'];
    }
}

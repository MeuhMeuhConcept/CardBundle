<?php

namespace MMC\CardBundle\Entity;

interface CardItem
{
    public function getStatus();

    public function setStatus($status);

    public function duplicate();

    public function setCard(Card $card = null);

    public function getCard();

    public function getValidationGroups();
}

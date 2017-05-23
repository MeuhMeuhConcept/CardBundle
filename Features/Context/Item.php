<?php

namespace MMC\CardBundle\Features\Context;

use MMC\CardBundle\Entity\AbstractCardItem;
use MMC\CardBundle\Entity\CardItem;

class Item extends AbstractCardItem
{
    protected $title;

    public function getSupportedCardClass()
    {
        return Card::class;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function copy(CardItem $item)
    {
        $this->setTitle($item->getTitle());

        return $item;
    }
}

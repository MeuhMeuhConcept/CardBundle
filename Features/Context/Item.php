<?php

namespace MMC\CardBundle\Features\Context;

use MMC\CardBundle\Entity\AbstractCardItem;

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

    public function duplicate()
    {
        $item = new self();
        $item->setCard($this->card)
                ->setTitle($this->title);

        return $item;
    }
}

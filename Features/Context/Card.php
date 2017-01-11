<?php

namespace MMC\CardBundle\Features\Context;

use MMC\CardBundle\Entity\AbstractCard;

class Card extends AbstractCard
{
    public function getId()
    {
        return 1;
    }

    public function getSupportedClass()
    {
        return Item::class;
    }
}

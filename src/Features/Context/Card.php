<?php

namespace MMC\CardBundle\Features\Context;

use MMC\CardBundle\Entity\AbstractCard;

class Card extends AbstractCard
{
    public function getSupportedClass()
    {
        return Item::class;
    }
}

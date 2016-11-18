<?php

namespace MMC\CardBundle\Entity;

interface CardViews
{
    public function getId();

    public function activeView($options = []);
}

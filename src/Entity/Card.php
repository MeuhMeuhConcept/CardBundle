<?php

namespace MMC\CardBundle\Entity;

interface Card
{
    public function getItems();

    public function getItemsByStatus($status);

    public function addItem(CardItem $item);

    public function removeItem(CardItem $item);

    public function getMainItem();

    public function getValid();

    public function getDraft();
}

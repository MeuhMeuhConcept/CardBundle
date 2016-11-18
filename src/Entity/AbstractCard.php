<?php

namespace MMC\CardBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use MMC\CardBundle\Model\Status;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractCard implements Card, CardViews
{
    use CardViewsTrait;

    abstract protected function getSupportedClass();

    protected $uuid;

    protected $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();

        try {
            $this->setUuid(Uuid::uuid4());
        } catch (UnsatisfiedDependencyException $e) {
        }
    }

    public function getUuid()
    {
        return $this->uuid;
    }

    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getItemsByStatus($status)
    {
        if (!is_array($status)) {
            $status = [$status];
        }

        $itemsByStatus = new ArrayCollection();

        foreach ($this->items as $item) {
            if (in_array($item->getStatus(), $status)) {
                $itemsByStatus->add($item);
            }
        }

        return $itemsByStatus;
    }

    public function addItem(CardItem $item)
    {
        $this->checkItem($item);

        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->setCard($this);
        }

        $this->refreshActiveViewInitialization();

        return $this;
    }

    public function removeItem(CardItem $item)
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            $item->setCard(null);
        }

        $this->refreshActiveViewInitialization();

        return $this;
    }

    private function checkItem(CardItem $item)
    {
        $class = $this->getSupportedClass();
        if (!$item instanceof $class) {
            throw new \UnexpectedValueException('Bad type, '.$class.' expected.');
        }
    }

    public function getMainItem()
    {
        return $this->getFirstItemInOrder([Status::VALID, Status::CREATING, Status::DRAFT]);
    }

    public function getValid()
    {
        return $this->getItemsByStatus(Status::VALID)->first();
    }

    /**
     * @Assert\Valid
     */
    public function getDraft()
    {
        return $this->getFirstItemInOrder([Status::CREATING, Status::DRAFT]);
    }

    protected function getFirstItemInOrder($status)
    {
        if (!is_array($status)) {
            $status = [$status];
        }

        foreach ($status as $s) {
            $items = $this->getItemsByStatus($s);

            if ($items->count()) {
                return $items->first();
            }
        }

        return;
    }
}

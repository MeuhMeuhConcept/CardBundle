<?php

namespace MMC\CardBundle\Tests\Entity;

use MMC\CardBundle\Entity\AbstractCard;
use MMC\CardBundle\Entity\CardItem;

class AbstractCardTest extends \PHPUnit_Framework_TestCase
{
    protected $card;

    public function setup()
    {
        $this->card = $this->getMockForAbstractClass(AbstractCard::class);

        $this->card->expects($this->any())
             ->method('getSupportedClass')
             ->will($this->returnValue(CardItem::class));
    }

    public function testAddItems()
    {
        $this->assertCount(0, $this->card->getItems());

        $item1 = $this->createMock(CardItem::class);
        $this->card->addItem($item1);
        $this->assertCount(1, $this->card->getItems());

        $this->card->addItem($item1);
        $this->assertCount(1, $this->card->getItems());

        $item2 = $this->createMock(CardItem::class);
        $this->card->addItem($item2);
        $this->assertCount(2, $this->card->getItems());
    }

    public function testRemoveItems()
    {
        $this->assertCount(0, $this->card->getItems());

        $item1 = $this->createMock(CardItem::class);
        $this->card->addItem($item1);
        $this->assertCount(1, $this->card->getItems());

        $this->card->removeItem($item1);
        $this->assertCount(0, $this->card->getItems());

        $item1 = $this->createMock(CardItem::class);
        $this->card->addItem($item1);
        $this->assertCount(1, $this->card->getItems());

        $item2 = $this->createMock(CardItem::class);
        $this->card->addItem($item2);
        $this->assertCount(2, $this->card->getItems());

        $this->card->removeItem($item1);
        $this->assertCount(1, $this->card->getItems());
    }

    /**
     * @expectedException TypeError
     */
    public function testAddItemTypeError()
    {
        $item1 = new \stdClass();

        $this->card->addItem($item1);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testAddItemUnexcepted()
    {
        $this->card = $this->getMockForAbstractClass(AbstractCard::class);

        $this->card->expects($this->any())
             ->method('getSupportedClass')
             ->will($this->returnValue(SomeCardItem::class));

        $item1 = $this->createMock(AnotherCardItem::class);
        $this->card->addItem($item1);
    }
}

abstract class SomeCardItem implements CardItem
{
}

abstract class AnotherCardItem implements CardItem
{
}

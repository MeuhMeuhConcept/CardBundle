<?php

namespace MMC\CardBundle\Features\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use MMC\CardBundle\Model\Action;
use MMC\CardBundle\Services\CardProcessor\ChainCardProcessor;
use MMC\CardBundle\Services\CardProcessor\Request;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CardProcessorContext implements Context, SnippetAcceptingContext
{
    protected $chainCardProcessor;
    protected $lastResponse;
    protected $nextRequest;
    protected $currentItem;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(
        ChainCardProcessor $chainCardProcessor
    ) {
        $this->chainCardProcessor = $chainCardProcessor;
    }

    /**
     * @Given I create a new card
     */
    public function iCreateANewCard()
    {
        $this->lastResponse = $this->chainCardProcessor->execute(new Request(Card::class, Action::CREATE));
    }

    /**
     * @Given I valide the current item
     */
    public function iValideTheCurrentItem()
    {
        $this->lastResponse = $this->chainCardProcessor->execute(new Request($this->currentItem, Action::VALIDATION));
    }

    /**
     * @Given I create a draft version of current item
     */
    public function iCreateADraftVersionOfCurrentItem()
    {
        $this->lastResponse = $this->chainCardProcessor->execute(new Request($this->currentItem, Action::EDIT));
    }

    /**
     * @Given I delete draft
     */
    public function iDeleteDraft()
    {
        $this->lastResponse = $this->chainCardProcessor->execute(new Request($this->currentItem, Action::DELETE_DRAFT));
    }

    /**
     * @Given I archived the card
     */
    public function iArchivedTheCard()
    {
        $this->lastResponse = $this->chainCardProcessor->execute(new Request($this->currentItem, Action::ARCHIVE));
    }

    /**
     * @Given I take the item with :field equal to :value
     */
    public function iTakeTheItemWithEqualTo($field, $value)
    {
        $items = $this->lastResponse->getCard()->getItems();

        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($items as $item) {
            if ($accessor->getValue($item, $field) == $value) {
                $this->currentItem = $item;
            }
        }

        \PHPUnit_Framework_Assert::assertNotNull($this->currentItem);
    }

    /**
     * @Given I set ":field" of current item to ":value"
     */
    public function iSetOfCurrentItemTo($field, $value)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $accessor->setValue($this->currentItem, $field, $value);
    }

    /**
     * @Given The ":field" of current item is equals to ":value"
     */
    public function theOfCurrentItemIsEqualsTo($field, $value)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        \PHPUnit_Framework_Assert::assertEquals($value, $accessor->getValue($this->currentItem, $field));
    }

    /**
     * @Then /^the card contains (?<count>(\d+)) items? with status "(?<status>(\w+))"$/
     */
    public function theCardContainsNbItemsWithStatus(int $count, $status)
    {
        \PHPUnit_Framework_Assert::assertCount($count, $this->lastResponse->getCard()->getItemsByStatus($status));
    }

    /**
     * @Then I should see the reason phrase :arg1 and the status code :arg2
     */
    public function iShouldSeeTheReasonPhraseAndTheStatusCode($arg1, $arg2)
    {
        \PHPUnit_Framework_Assert::assertEquals($arg1, $this->lastResponse->getReasonPhrase());

        \PHPUnit_Framework_Assert::assertEquals($arg2, $this->lastResponse->getStatusCode());
    }
}

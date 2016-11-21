<?php

namespace MMC\CardBundle\Services\CardProcessor;

abstract class AbstractSingleMoveCardProcessor implements CardProcessor
{
    abstract protected function getSupportedAction();

    abstract protected function getSupportedStatus();

    abstract protected function getExpectedStatus();

    public function support(Request $request)
    {
        if ($request->getAction() !== $this->getSupportedAction()) {
            return false;
        }

        if ($request->getCardItem() === null) {
            return false;
        }

        if (!in_array($request->getCardItem()->getStatus(), $this->getSupportedStatus())) {
            return false;
        }

        return true;
    }

    public function execute(Request $request)
    {
        if (!$this->support($request)) {
            return new Response($request, Response::STATUS_KO, 'request_is_not_supported');
        }

        $card = $request->getCardItem()->getCard();
        $validItem = $request->getCardItem()->setStatus($this->getExpectedStatus());

        $response = new Response($request, Response::STATUS_OK, 'request_is_ok');
        $response->setCard($card);

        return $response;
    }
}

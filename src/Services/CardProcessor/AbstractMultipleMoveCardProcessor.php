<?php

namespace MMC\CardBundle\Services\CardProcessor;

abstract class AbstractMultipleMoveCardProcessor implements CardProcessor
{
    abstract protected function getSupportedAction();

    abstract protected function getSupportedStatus();

    abstract protected function getExpectedStatus();

    public function support(Request $request)
    {
        if ($request->getAction() !== $this->getSupportedAction()) {
            return false;
        }

        if ($request->getCard() === null) {
            return false;
        }

        return true;
    }

    public function execute(Request $request)
    {
        if (!$this->support($request)) {
            return new Response($request, Response::STATUS_KO, 'request_is_not_supported');
        }

        $validItems = $request->getCard()->getItemsByStatus($this->getSupportedStatus());
        foreach ($validItems as $validItem) {
            $validItem->setStatus($this->getExpectedStatus());
        }

        $response = new Response($request, Response::STATUS_OK, 'request_is_ok');
        $response->setCard($request->getCard());

        return $response;
    }
}

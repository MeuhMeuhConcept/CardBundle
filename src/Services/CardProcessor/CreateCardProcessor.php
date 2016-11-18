<?php

namespace MMC\CardBundle\Services\CardProcessor;

use MMC\CardBundle\Model\Action;
use MMC\CardBundle\Model\Status;

class CreateCardProcessor implements CardProcessor
{
    public function support(Request $request)
    {
        if ($request->getAction() !== Action::CREATE) {
            return false;
        }

        if ($request->getCard() !== null) {
            return false;
        }

        return true;
    }

    public function execute(Request $request)
    {
        if (!$this->support($request)) {
            return new Response($request, Response::STATUS_KO, 'request_is_not_supported');
        }

        $cardClass = $request->getClassName();
        $card = new $cardClass();

        $itemClass = $card->getSupportedClass();
        $item = new $itemClass();
        $item->setCard($card)
            ->setStatus(Status::CREATING)
            ;

        $response = new Response($request, Response::STATUS_OK, 'request_is_ok');
        $response->setCard($card);

        return $response;
    }
}

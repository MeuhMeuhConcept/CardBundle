<?php

namespace MMC\CardBundle\Services\CardProcessor;

use MMC\CardBundle\Model\Action;
use MMC\CardBundle\Model\Status;

class EditCardProcessor implements CardProcessor
{
    public function support(Request $request)
    {
        if ($request->getAction() !== Action::EDIT) {
            return false;
        }

        if ($request->getCardItem() === null) {
            return false;
        }
        if ($request->getCardItem()->getStatus() !== Status::VALID) {
            return false;
        }

        return true;
    }

    public function execute(Request $request)
    {
        if (!$this->support($request)) {
            return new Response($request, Response::STATUS_KO, 'request_is_not_supported');
        }

        if (count($request->getCard()->getItemsByStatus(Status::DRAFT)) >= 1) {
            $response = new Response($request, Response::STATUS_OK, 'draft_already_exist');
            $response->setCard($request->getCard());

            return $response;
        }

        $draftItem = $request->getCardItem()->duplicate();
        $draftItem->setStatus(Status::DRAFT);

        $response = new Response($request, Response::STATUS_OK, 'request_is_ok');
        $response->setCard($draftItem->getCard());

        return $response;
    }
}

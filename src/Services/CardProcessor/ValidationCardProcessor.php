<?php

namespace MMC\CardBundle\Services\CardProcessor;

use MMC\CardBundle\Model\Action;
use MMC\CardBundle\Model\Status;
use MMC\CardBundle\Services\CardItemValidator;

class ValidationCardProcessor implements CardProcessor
{
    protected $validator;

    public function __construct(
        CardItemValidator $validator
    ) {
        $this->validator = $validator;
    }

    public function support(Request $request)
    {
        if ($request->getAction() !== Action::VALIDATION) {
            return false;
        }

        if ($request->getCardItem() === null) {
            return false;
        }
        if (!in_array($request->getCardItem()->getStatus(), [Status::CREATING, Status::DRAFT])) {
            return false;
        }

        return true;
    }

    public function execute(Request $request)
    {
        if (!$this->support($request)) {
            return new Response($request, Response::STATUS_KO, 'request_is_not_supported');
        }

        $item = $request->getCardItem();

        $errors = $this->validator->validate($item);

        if (count($errors)) {
            return new Response($request, Response::STATUS_KO, $errors);
        }

        $validItems = $request->getCard()->getItemsByStatus(Status::VALID);
        foreach ($validItems as $validItem) {
            $validItem->setStatus(Status::ARCHIVED);
        }

        $item->setStatus(Status::VALID);

        $response = new Response($request, Response::STATUS_OK, 'request_is_ok');
        $response->setCard($item->getCard());

        return $response;
    }
}

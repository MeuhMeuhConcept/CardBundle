<?php

namespace MMC\CardBundle\Services\CardProcessor;

use MMC\CardBundle\Model\Action;
use MMC\CardBundle\Model\Status;

class DeleteSingleDraftCardProcessor extends AbstractSingleMoveCardProcessor
{
    protected function getSupportedAction()
    {
        return Action::DELETE_DRAFT;
    }

    protected function getSupportedStatus()
    {
        return [Status::DRAFT, Status::CREATING];
    }

    protected function getExpectedStatus()
    {
        return Status::DELETED;
    }
}

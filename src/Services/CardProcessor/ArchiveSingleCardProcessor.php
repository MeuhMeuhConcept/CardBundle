<?php

namespace MMC\CardBundle\Services\CardProcessor;

use MMC\CardBundle\Model\Action;
use MMC\CardBundle\Model\Status;

class ArchiveSingleCardProcessor extends AbstractSingleMoveCardProcessor
{
    protected function getSupportedAction()
    {
        return Action::ARCHIVE;
    }

    protected function getSupportedStatus()
    {
        return [Status::VALID];
    }

    protected function getExpectedStatus()
    {
        return Status::ARCHIVED;
    }
}

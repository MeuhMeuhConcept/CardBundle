<?php

namespace MMC\CardBundle\Model;

use Greg0ire\Enum\AbstractEnum;

final class Action extends AbstractEnum
{
    const ARCHIVE = 'A';
    const CREATE = 'C';
    const DELETE_DRAFT = 'D';
    const EDIT = 'E';
    const VALIDATION = 'V';
}

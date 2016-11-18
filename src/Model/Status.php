<?php

namespace MMC\CardBundle\Model;

use Greg0ire\Enum\AbstractEnum;

final class Status extends AbstractEnum
{
    const CREATING = 'c';
    const ARCHIVED = 'a';
    const DRAFT = 'd';
    const VALID = 'v';
    const DELETED = 'x';
}

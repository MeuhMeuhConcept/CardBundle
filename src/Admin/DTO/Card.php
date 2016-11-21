<?php

namespace MMC\CardBundle\Admin\DTO;

use MMC\CardBundle\Model\Status;

class Card
{
    protected $id;

    protected $uuid;

    protected $status;

    protected $isDraft;

    public function __construct(
        $id,
        $uuid,
        $status,
        $isDraft
    ) {
        $this->id = $id;
        $this->uuid = $uuid;
        $this->status = $status;
        $this->isDraft = $isDraft;
    }

    /**
     * @return id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return uuid
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    public function isValid()
    {
        return $this->status == Status::VALID;
    }

    public function isDraft()
    {
        return $this->isDraft || $this->status == Status::CREATING;
    }
}

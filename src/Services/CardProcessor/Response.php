<?php

namespace MMC\CardBundle\Services\CardProcessor;

class Response
{
    const STATUS_OK = 'OK';
    const STATUS_KO = 'KO';

    protected $statusCode;
    protected $reasonPhrase;
    protected $card;

    public function __construct(Request $request, $statusCode, $reasonPhrase = '')
    {
        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
    }

    public function setCard($card)
    {
        $this->card = $card;

        return $this;
    }

    public function getCard()
    {
        return $this->card;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }
}

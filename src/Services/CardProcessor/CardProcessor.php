<?php

namespace MMC\CardBundle\Services\CardProcessor;

interface CardProcessor
{
    public function support(Request $request);

    public function execute(Request $request);
}

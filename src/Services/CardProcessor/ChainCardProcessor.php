<?php

namespace MMC\CardBundle\Services\CardProcessor;

class ChainCardProcessor implements CardProcessor
{
    protected $cardProcessors;

    public function __construct()
    {
        $this->cardProcessors = [];
    }

    public function support(Request $request)
    {
        foreach ($this->cardProcessors as $cardProcessor) {
            if ($cardProcessor->support($request)) {
                return true;
            }
        }

        return false;
    }

    public function execute(Request $request)
    {
        foreach ($this->cardProcessors as $cardProcessor) {
            if ($cardProcessor->support($request)) {
                return $cardProcessor->execute($request);
            }
        }

        return new Response($request, Response::STATUS_KO, 'no_processor_found');
    }

    public function addCardProcessor(CardProcessor $cardProcessor)
    {
        $this->cardProcessors[] = $cardProcessor;
    }
}

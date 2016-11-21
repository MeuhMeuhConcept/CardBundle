<?php

namespace MMC\CardBundle\Twig;

use MMC\CardBundle\Services\CardItemValidator;

class MMCCardExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    protected $validator;

    public function __construct(
        CardItemValidator $validator
    ) {
        $this->validator = $validator;
    }

    public function getGlobals()
    {
        return [];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('validate', [$this, 'validate']),
        ];
    }

    public function getFilters()
    {
        return [];
    }

    public function getTests()
    {
        return [
            new \Twig_SimpleTest('cardInstance', [$this, 'testCardInstance']),
        ];
    }

    public function getName()
    {
        return 'mmc_card_extension';
    }

    public function testCardInstance($object)
    {
        return $object instanceof \MMC\CardBundle\Entity\Card;
    }

    public function validate($object, $formatError = true, $separator = '<br />')
    {
        return $this->validator->validate($object, $formatError, $separator);
    }
}

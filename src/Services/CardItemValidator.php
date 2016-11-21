<?php

namespace MMC\CardBundle\Services;

use MMC\CardBundle\Entity\CardItem;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CardItemValidator
{
    protected $validator;

    protected $translator;

    public function __construct(
        ValidatorInterface $validator,
        TranslatorInterface $translator
    ) {
        $this->validator = $validator;
        $this->translator = $translator;
    }

    public function validate(CardItem $item, $formatError = true, $separator = '<br />')
    {
        $errors = $this->validator->validate($item, null, ['Default', 'validate']);

        if (count($errors)) {
            if ($formatError) {
                $errorsMessages = [];
                foreach ($errors as $key => $value) {
                    $catalog = preg_replace('/^.*\\\\/', '', $value->getRoot()->getSupportedCardClass());

                    $errorsMessages[] = $this->translator->trans($value->getPropertyPath(), [], $catalog).' : '.$value->getMessage();
                }

                return implode($separator, $errorsMessages);
            }

            return $errors;
        }

        return [];
    }
}

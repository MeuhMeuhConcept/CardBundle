<?php

namespace MMC\CardBundle\Form\Type;

use MMC\CardBundle\Entity\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusValidationType extends AbstractType
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['card'] = $options['card'];
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'card',
        ]);
        $resolver->setDefaults([
            'required' => false,
            'disabled' => true,
        ]);
        $resolver->setAllowedTypes('card', Card::class);
    }
}

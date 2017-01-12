<?php

namespace MMC\CardBundle\Entity;

use MMC\CardBundle\Model\Status;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait CardViewsTrait
{
    protected $activeView;

    private $activeViewInitialized = false;

    private $lastActiveViewOptions;

    abstract public function getId();

    abstract public function getItemsByStatus($status);

    abstract public function getSupportedClass();

    public function activeView($options = [])
    {
        $resolver = new OptionsResolver();

        $resolver->setDefaults([
            'item' => null,
            'status' => Status::VALID,
        ]);

        $resolver->setAllowedTypes('item', [$this->getSupportedClass(), 'null']);
        $resolver->setAllowedTypes('status', 'string');
        $resolver->setAllowedValues('status', Status::getConstants());

        $options = $resolver->resolve($options);

        $this->lastActiveViewOptions = $options;

        $this->activeView = null;

        if ($options['item'] && $options['item']->getCard() == $this) {
            $this->activeView = $options['item'];
        } elseif ($options['status']) {
            $this->activeView = $this->getItemsByStatus($options['status'])->first();
        }

        $this->activeViewInitialized = true;
    }

    public function getActiveView()
    {
        if (!$this->activeView && !$this->activeViewInitialized) {
            $this->activeView();
        }

        return $this->activeView;
    }

    protected function refreshActiveViewInitialization()
    {
        if ($this->activeViewInitialized) {
            $this->activeView($this->lastActiveViewOptions);
        }
    }
}

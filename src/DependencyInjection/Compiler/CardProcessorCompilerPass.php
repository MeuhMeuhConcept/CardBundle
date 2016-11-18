<?php

namespace MMC\CardBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CardProcessorCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('mmc_card.ChainCardProcessor');
        $taggedServices = $container->findTaggedServiceIds('mmc_card.ChainCardProcessor');
        foreach ($taggedServices as $id => $properties) {
            $definition->addMethodCall(
                'addCardProcessor',
                [new Reference($id)]
            );
        }
    }
}

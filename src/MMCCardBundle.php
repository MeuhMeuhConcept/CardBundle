<?php

namespace MMC\CardBundle;

use MMC\CardBundle\DependencyInjection\Compiler\CardProcessorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MMCCardBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new CardProcessorCompilerPass());
    }
}

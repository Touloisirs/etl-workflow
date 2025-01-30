<?php

namespace Touloisirs\ETLWorkflow;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class TouloisirsETLWorkflowBundle extends AbstractBundle
{
    /** @param array<mixed> $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->services()
            ->set(Client::class)
            ->alias(ClientInterface::class, Client::class)
        ;
    }
}

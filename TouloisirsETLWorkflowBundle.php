<?php

namespace Touloisirs\ETLWorkflow;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TouloisirsETLWorkflowBundle extends AbstractBundle
{
    /** @param array<mixed> $config */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
    }
}

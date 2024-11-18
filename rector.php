<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/Context',
        __DIR__.'/Event',
        __DIR__.'/Extractor',
        __DIR__.'/Loader',
        __DIR__.'/Transformer',
        __DIR__.'/Workflow',
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withTypeCoverageLevel(0);

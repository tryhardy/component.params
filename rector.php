<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/install',
        __DIR__ . '/lang',
        __DIR__ . '/lib',
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withTypeCoverageLevel(0)
	->withParallel(360)
	->withImportNames();

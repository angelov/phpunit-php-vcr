<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr;

use Angelov\PHPUnitPHPVcr\Subscribers\ConfigureRecorder;
use Angelov\PHPUnitPHPVcr\Subscribers\FinishRecording;
use Angelov\PHPUnitPHPVcr\Subscribers\StartRecording;
use PHPUnit\Runner\Extension as PHPUnit;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

class Extension implements PHPUnit\Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber(
            new ConfigureRecorder(
                $this->parameter($parameters, 'cassettesPath'),
                $this->parameter($parameters, 'storage'),
                $this->parameterAsArray($parameters, 'libraryHooks'),
                $this->parameterAsArray($parameters, 'requestMatchers'),
                $this->parameterAsArray($parameters, 'whitelistedPaths'),
                $this->parameterAsArray($parameters, 'blacklistedPaths'),
                $this->parameter($parameters, 'mode')
            )
        );

        $facade->registerSubscriber(new StartRecording());
        $facade->registerSubscriber(new FinishRecording());
    }

    private function parameter(ParameterCollection $parameters, string $name): ?string
    {
        if ($parameters->has($name)) {
            return $parameters->get($name);
        }

        return null;
    }

    /** @return array<string>|null */
    private function parameterAsArray(ParameterCollection $parameters, string $name): ?array
    {
        $value = $this->parameter($parameters, $name);

        if ($value == null) {
            return null;
        }

        $data = [];
        foreach(explode(',', $value) as $item)
        {
            $data[] = trim($item);
        }

        return $data;
    }
}

<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr\Subscribers;

use Angelov\PHPUnitPHPVcr\UseCassette;
use Exception;
use ReflectionMethod;

trait AttributeResolverTrait
{
    private function needsRecording(string $test): bool
    {
        return $this->getAttribute($test) !== null;
    }

    private function getCassetteName(string $test): ?string
    {
        return $this->getAttribute($test)?->name;
    }

    private function getAttribute(string $test): ?UseCassette
    {
        $test = $this->parseMethod($test);

        try {
            // @phpstan-ignore-next-line
            if (method_exists(ReflectionMethod::class, 'createFromMethodName')) {
                $method = ReflectionMethod::createFromMethodName($test);
            } else {
                $method = new ReflectionMethod($test);
            }
        } catch (Exception) {
            return null;
        }

        $attributes = $method->getAttributes(UseCassette::class);

        if ($attributes) {
            return $attributes[0]->newInstance();
        }

        return $this->getAttributeFromClass($test);
    }

    private function parseMethod(string $test): string
    {
        $test = explode(" ", $test)[0];

        return explode("#", $test)[0];
    }

    private function getAttributeFromClass(string $test): ?UseCassette
    {
        // @phpstan-ignore-next-line
        if (method_exists(ReflectionMethod::class, 'createFromMethodName')) {
            $method = ReflectionMethod::createFromMethodName($test);
        } else {
            $method = new ReflectionMethod($test);
        }

        $class = $method->getDeclaringClass();
        $attributes = $class->getAttributes(UseCassette::class);

        if ($attributes) {
            return $attributes[0]->newInstance();
        }

        return null;
    }
}

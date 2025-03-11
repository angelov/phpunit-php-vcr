<?php

declare(strict_types=1);

/**
 * This file is part of Angelov phpunit-vcr.
 *
 * (c) Angelov <https://angelovdejan.me>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Angelov\PHPUnitPHPVcr\Subscribers;

use Angelov\PHPUnitPHPVcr\UseCassette;
use Exception;
use PHPUnit\Event\Code\Test;
use PHPUnit\Event\Code\TestMethod;
use ReflectionClass;
use ReflectionMethod;

trait AttributeResolverTrait
{
    private function needsRecording(Test $test): bool
    {
        return $this->getAttribute($test) !== null;
    }

    private function getCassetteName(Test $test): ?string
    {
        return $this->getAttribute($test)?->name;
    }

    private function getAttribute(Test $test): ?UseCassette
    {
        $reflection = new ReflectionClass($test);
        $class      = $reflection->getProperty('className')->getValue($test);

        if ($test instanceof TestMethod) {
            $method = $test->methodName();
        } else {
            $method = $test->name();
        }

        try {
            $method = new ReflectionMethod($class, $method);
        } catch (Exception) {
            return null;
        }

        $attributes = $method->getAttributes(UseCassette::class);

        if ($attributes) {
            return $attributes[0]->newInstance();
        }
        $class      = $method->getDeclaringClass();
        $attributes = $class->getAttributes(UseCassette::class);

        if ($attributes) {
            return $attributes[0]->newInstance();
        }

        return null;
    }
}

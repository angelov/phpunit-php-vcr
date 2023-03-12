<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class UseCassette
{
    public function __construct(public readonly string $name)
    {
    }
}

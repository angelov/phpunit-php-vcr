<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
readonly class UseCassette
{
    public function __construct(
        public string $name,
        public bool $separateCassettePerCase = false,
        public bool $groupCaseFilesInDirectory = false
    ) {
    }
}

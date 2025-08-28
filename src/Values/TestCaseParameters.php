<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr\Values;

use Angelov\PHPUnitPHPVcr\UseCassette;

readonly class TestCaseParameters
{
    public function __construct(
        public UseCassette $cassetteInfo,
        public ?string $case = null,
    ) {
    }
}

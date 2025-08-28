<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr\Tests;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WithoutVcrTest extends TestCase
{
    #[Test]
    public function it_uses_vcr_on_methods_with_attribute(): void
    {
        // @phpstan-ignore-next-line method.alreadyNarrowedType
        $this->assertTrue(true);
    }
}

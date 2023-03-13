<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr\Tests;

use Angelov\PHPUnitPHPVcr\UseCassette;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[UseCassette("combined_on_class.yml")]
class AttributeDeclaredBothOnClassAndMethodTest extends TestCase
{
    #[Test]
    #[UseCassette("on_methods.yml")]
    public function it_uses_cassette_from_method_when_declared_on_both_places(): void
    {
        $content = file_get_contents("https://example.com");

        $this->assertSame("Example body.", $content);
    }
}

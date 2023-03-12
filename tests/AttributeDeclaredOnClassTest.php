<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr\Tests;

use Angelov\PHPUnitPHPVcr\UseCassette;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[UseCassette("on_class.yml")]
class AttributeDeclaredOnClassTest extends TestCase
{
    #[Test]
    public function it_uses_vcr_on_methods_from_class_with_attribute(): void
    {
        $content = file_get_contents("https://example.com");

        $this->assertSame("Example body for \"https://example.com\"", $content);
    }

    #[Test]
    #[DataProvider("urls")]
    public function it_uses_vcr_on_methods_with_data_provider(string $url): void
    {
        $content = file_get_contents($url);

        $this->assertSame(sprintf("Example body for \"%s\"", $url), $content);
    }

    /** @return iterable<list<string>> */
    public static function urls(): iterable
    {
        yield ["https://example.com"];
        yield ["https://example.org"];
    }
}

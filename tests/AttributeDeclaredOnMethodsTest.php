<?php

declare(strict_types=1);

namespace Angelov\PHPUnitPHPVcr\Tests;

use Angelov\PHPUnitPHPVcr\UseCassette;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AttributeDeclaredOnMethodsTest extends TestCase
{
    #[Test]
    #[UseCassette("on_methods.yml")]
    public function it_uses_vcr_on_methods_with_attribute(): void
    {
        $content = file_get_contents("https://example.com");

        $this->assertSame("Example body.", $content);
    }

    #[Test]
    #[UseCassette("with_data_provider.yml")]
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

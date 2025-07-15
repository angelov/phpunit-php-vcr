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
    #[UseCassette("on_methods_without_extension")]
    public function it_uses_vcr_on_methods_with_attribute_cassette_without_extension(): void
    {
        $content = file_get_contents("https://example.com");

        $this->assertSame("Example body.", $content);
    }

    #[Test]
    #[UseCassette(
        name: "on_methods_without_extension_with_data_provider",
        separateCassettePerCase: true,
        groupCaseFilesInDirectory:true,
    )]
    #[DataProvider("namedUrls")]
    public function it_uses_vcr_on_methods_with_attribute_cassette_and_data_provider_without_extension(string $url): void
    {
        $content = file_get_contents($url);

        $this->assertSame(sprintf("Example body for \"%s\"", $url), $content);
    }

    #[Test]
    #[UseCassette("with_data_provider.yml")]
    #[DataProvider("urls")]
    public function it_uses_vcr_on_methods_with_data_provider(string $url): void
    {
        $content = file_get_contents($url);

        $this->assertSame(sprintf("Example body for \"%s\"", $url), $content);
    }

    #[Test]
    #[UseCassette(name: "with_data_provider_and_separated_cassettes.yml", separateCassettePerCase: true)]
    #[DataProvider("namedUrls")]
    public function it_uses_vcr_on_methods_with_data_provider_and_separate_cassette_per_case(string $url): void
    {
        $content = file_get_contents($url);

        $this->assertSame(sprintf("Example body for \"%s\"", $url), $content);
    }

    #[Test]
    #[UseCassette(
        name: "with_data_provider_and_separated_cassettes_in_directory.yml",
        separateCassettePerCase: true,
        groupCaseFilesInDirectory: true,
    )]
    #[DataProvider("urls")]
    public function it_uses_vcr_on_methods_with_data_provider_and_separate_cassette_per_case_in_directories(string $url): void
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

    /** @return iterable<string, list<string>> */
    public static function namedUrls(): iterable
    {
        yield 'example.com' => ["https://example.com"];
        yield 'example.org' => ["https://example.org"];
        yield 'test case with spaces' => ["https://example.org"];
    }
}

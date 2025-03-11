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

namespace Angelov\PHPUnitPHPVcr\Tests;

use Angelov\PHPUnitPHPVcr\UseCassette;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class AttributeDeclaredOnMethodsTest extends TestCase
{
    #[Test]
    #[UseCassette('on_methods.yml')]
    public function itUsesVcrOnMethodsWithAttribute(): void
    {
        $content = file_get_contents('https://example.com');

        $this->assertSame('Example body.', $content);
    }

    #[DataProvider('urls')]
    #[Test]
    #[UseCassette('with_data_provider.yml')]
    public function itUsesVcrOnMethodsWithDataProvider(string $url): void
    {
        $content = file_get_contents($url);

        $this->assertSame(sprintf('Example body for "%s"', $url), $content);
    }

    /**
     * @return iterable<list<string>>
     */
    public static function urls(): iterable
    {
        yield ['https://example.com'];

        yield ['https://example.org'];
    }
}

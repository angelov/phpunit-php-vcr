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
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[UseCassette('combined_on_class.yml')]
final class AttributeDeclaredBothOnClassAndMethodTest extends TestCase
{
    #[Test]
    #[UseCassette('on_methods.yml')]
    public function itUsesCassetteFromMethodWhenDeclaredOnBothPlaces(): void
    {
        $content = file_get_contents('https://example.com');

        $this->assertSame('Example body.', $content);
    }
}

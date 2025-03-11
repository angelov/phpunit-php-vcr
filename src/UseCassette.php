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

namespace Angelov\PHPUnitPHPVcr;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class UseCassette
{
    public function __construct(public readonly string $name)
    {
    }
}

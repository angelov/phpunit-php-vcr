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

namespace Angelov\PHPUnitPHPVcr\Subscribers;

use PHPUnit\Event\Test\Prepared;
use PHPUnit\Event\Test\PreparedSubscriber;
use VCR\VCR;

class StartRecording implements PreparedSubscriber
{
    use AttributeResolverTrait;

    public function notify(Prepared $event): void
    {
        $test = $event->test();

        if (! $this->needsRecording($test)) {
            return;
        }

        $cassetteName = $this->getCassetteName($test);

        assert($cassetteName !== null);

        VCR::turnOn();
        VCR::insertCassette($cassetteName);
    }
}

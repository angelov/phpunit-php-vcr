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

use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber;
use VCR\VCR;

class FinishRecording implements FinishedSubscriber
{
    use AttributeResolverTrait;

    public function notify(Finished $event): void
    {
        $test = $event->test();

        if (! $this->needsRecording($test)) {
            return;
        }

        VCR::eject();
        VCR::turnOff();
    }
}
